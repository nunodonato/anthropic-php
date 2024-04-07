<?php

namespace Nunodonato\AnthropicAPIPHP;

use Illuminate\Http\Client\PendingRequest;

class Client
{
    const MODEL_OPUS = 'claude-3-opus-20240229';
    const MODEL_SONNET = 'claude-3-sonnet-20240229';
    const MODEL_HAIKU = 'claude-3-haiku-20240307';

    const MODEL_CLAUDE_2 = 'claude-2.1';

    private PendingRequest $pendingRequest;

    public function __construct(string $apiKey, string $version = '2023-06-01')
    {
        $this->pendingRequest = new PendingRequest();

        // x-api-key is the header name for the API key
        $this->pendingRequest->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => $version,
                'content-type' => 'application/json'
            ]
        );
    }

    public function message(string $model, array $messages, string $systemPrompt = '', array $tools = [], array $stopSequences = [], int $maxTokens = 1000, float $temperature = 1.0, ?float $topP = null, ?float $topK = null, bool $stream = false): array
    {
        $data = [
            'model' => $model,
            'messages' => $messages,
            'system' => $systemPrompt,
            'max_tokens' => $maxTokens,
        ];

        $optionalParams = [
            'tools' => count($tools) > 0 ? $tools : null,
            'stop_sequences' => count($stopSequences) > 0 ? $stopSequences : null,
            'temperature' => $temperature !== 1.0 ? $temperature : null,
            'top_p' => $topP !== null ? $topP : null,
            'top_k' => $topK !== null ? $topK : null,
            'stream' => $stream ? true : null,
        ];

        $data = array_merge($data, array_filter($optionalParams, function ($value) {
            return $value !== null;
        }));

        $response = $this->pendingRequest->post('https://api.anthropic.com/v1/messages', $data);

        return $response->json();
    }

    public function completion(string $model, string $prompt, int $maxTokens = 1000, array $stopSequences = [], float $temperature = 1.0, ?float $topP = null, ?float $topK = null, bool $stream = false): array
    {
        $data = [
            'model' => $model,
            'prompt' => $prompt,
            'max_tokens_to_sample' => $maxTokens,
        ];

        $optionalParams = [
            'stop_sequences' => count($stopSequences) > 0 ? $stopSequences : null,
            'temperature' => $temperature !== 1.0 ? $temperature : null,
            'top_p' => $topP !== null ? $topP : null,
            'top_k' => $topK !== null ? $topK : null,
            'stream' => $stream ? true : null,
        ];

        $data = array_merge($data, array_filter($optionalParams, function ($value) {
            return $value !== null;
        }));

        $response = $this->pendingRequest->post('https://api.anthropic.com/v1/complete', $data);

        return $response->json();
    }

}