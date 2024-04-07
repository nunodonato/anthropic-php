<?php

namespace Nunodonato\AnthropicAPIPHP\Tests;

use Env\Dotenv;
use PHPUnit\Framework\TestCase;
use Nunodonato\AnthropicAPIPHP\Client;

class AnthropicAPIClientTest extends TestCase
{
    private ?Client $client = null;

    protected function setUp(): void
    {
        $env = Dotenv::toArray(
            path: '.env',
            strict: false, // by default: true
        );

        $this->client = new Client($env['ANTHROPIC_API_KEY']);
    }


    public function test_completion()
    {
        $response = $this->client->completion(Client::MODEL_CLAUDE_2, "\n\nHuman: Hello!\n\nAssistant: ");
        $this->assertArrayHasKey('completion', $response);
        $this->assertArrayNotHasKey('error', $response);
    }

    public function test_messages()
    {
        $messages = [
            [
                'role' => 'user',
                'content' => 'Hello!',
            ]
        ];

        $response = $this->client->message(Client::MODEL_SONNET, $messages);

        $this->assertArrayHasKey('content', $response);
        $this->assertArrayNotHasKey('error', $response);
    }
}