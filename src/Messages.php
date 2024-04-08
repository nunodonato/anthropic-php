<?php

namespace Nunodonato\AnthropicAPIPHP;

class Messages
{
    const ROLE_USER = 'user';
    const ROLE_ASSISTANT = 'assistant';

    private array $messages = [];


    public function messages(): array
    {
        return $this->messages;
    }
    public function addUserTextMessage(string $text): self
    {
        return $this->addMessage(self::ROLE_USER, $text);
    }

    public function addUserImageMessage(string $url, ?string $text = null): self
    {
        $contents = file_get_contents($url);
        if (!$contents) {
            throw new \InvalidArgumentException('Invalid image URL');
        }

        $base64 = base64_encode($contents);
        $mediaType = mime_content_type($url);

        $imageMessage = [
            'type' => 'image',
            'source' => [
                'type' => 'base64',
                'data' => $base64,
                'media_type' => $mediaType
            ],
        ];
        $message = [ $imageMessage ];

        if ($text) {
            $textMessage = [
                'role' => self::ROLE_USER,
                'content' => $text
            ];
            $message[] = $textMessage;
        }

        $this->messages[] = $message;

        return $this;
    }

    public function addAssistantTextMessage(string $text): self
    {
        return $this->addMessage(self::ROLE_ASSISTANT, $text);
    }

    public function addMessage(string $role, string|array $content): self
    {
        if (!in_array($role, [self::ROLE_USER, self::ROLE_ASSISTANT])) {
            throw new \InvalidArgumentException('Invalid role');
        }

        if (is_array($content)) {
            foreach ($content as $i => $message) {
                if (!is_array($message)) {
                    throw new \InvalidArgumentException('Message must be an array. Index: ' . $i);
                }
                if (!array_key_exists('type', $message)) {
                    throw new \InvalidArgumentException('Message type is required. Index: ' . $i);
                }
                if ($message['type'] == 'text' && !array_key_exists('text', $message)) {
                    throw new \InvalidArgumentException('Text property is required for text type. Index: ' . $i);
                }
                if ($message['type'] == 'image' && (!array_key_exists('media_type', $message) || !array_key_exists('data', $message))) {
                    throw new \InvalidArgumentException('Media type and data property are required for image type. Index: ' . $i);
                }
            }

            return $this;
        }

        $this->messages[] = [
            'role' => $role,
            'content' => $content,
        ];

        return $this;
    }
}