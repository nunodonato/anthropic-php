<?php

namespace NunoDonato\AnthropicAPIPHP;

use Psr\Http\Message\StreamInterface;
use Generator;

class StreamResponse
{
    private StreamInterface $stream;

    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    public function getEvents(): Generator
    {
        $buffer = '';
        while (!$this->stream->eof()) {
            $chunk = $this->stream->read(1024);
            $buffer .= $chunk;

            $events = $this->parseEvents($buffer);
            foreach ($events as $event) {
                yield $event;
            }

            $buffer = $this->getRemaining($buffer);
        }
    }

    private function parseEvents(string $buffer): array
    {
        $events = [];
        $lines = explode("\n\n", $buffer);

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $event = $this->parseEvent($line);
            if ($event) {
                $events[] = $event;
            }
        }

        return $events;
    }

    private function parseEvent(string $eventString): ?array
    {
        $lines = explode("\n", $eventString);
        $event = ['type' => '', 'data' => ''];

        foreach ($lines as $line) {
            if (strpos($line, 'event:') === 0) {
                $event['type'] = trim(substr($line, 6));
            } elseif (strpos($line, 'data:') === 0) {
                $event['data'] = json_decode(trim(substr($line, 5)), true);
            }
        }

        return $event['type'] && $event['data'] ? $event : null;
    }

    private function getRemaining(string $buffer): string
    {
        $lastNewLine = strrpos($buffer, "\n\n");
        return $lastNewLine !== false ? substr($buffer, $lastNewLine + 2) : $buffer;
    }
}