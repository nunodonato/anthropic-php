<?php

namespace NunoDonato\AnthropicAPIPHP\Tests;

use Env\Dotenv;
use NunoDonato\AnthropicAPIPHP\Messages;
use NunoDonato\AnthropicAPIPHP\Tools;
use PHPUnit\Framework\TestCase;
use NunoDonato\AnthropicAPIPHP\Client;

class AnthropicAPIClientTest extends TestCase
{
    private ?Client $client = null;

    private string $apiKey = '';
    protected function setUp(): void
    {
        $env = Dotenv::toArray(
            path: '.env',
            strict: false, // by default: true
        );

        $this->apiKey = $env['ANTHROPIC_API_KEY'];

        $this->client = new Client($this->apiKey);
    }


    public function test_completion()
    {
        $response = $this->client->completion(Client::MODEL_CLAUDE_2, "\n\nHuman: Hello!\n\nAssistant: ");
        $this->assertArrayHasKey('completion', $response);
        $this->assertArrayNotHasKey('error', $response);
    }

    public function test_messages()
    {
        $messages = new Messages();
        $messages->addUserTextMessage('Hello!');
        $messages->addAssistantTextMessage('Not so fast');

        $response = $this->client->messages(Client::MODEL_SONNET, $messages);

        $this->assertArrayHasKey('content', $response);
        $this->assertArrayNotHasKey('error', $response);
    }

    public function test_tools()
    {
        $client = new Client($this->apiKey, useBeta: true);
        $weatherTool = [
            'name' => 'weather',
            'description' => 'Get the weather in a given location',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'city' => [
                        'type' => 'string',
                        'description' => 'City name'
                    ],
                    'country_code' => [
                        'type' => 'string',
                        'description' => '2-letter Country code'
                    ]
                ],
                'required' => ['city']
            ]
        ];

        $tools = new Tools();
        $tools->addToolFromArray($weatherTool);
        $messages = new Messages();
        $messages->addUserTextMessage('What is the weather in Lisbon, Portugal?');
        $response = $client->messages(Client::MODEL_SONNET, $messages, tools: $tools);

        $this->assertArrayHasKey('content', $response);
        $toolUse = array_filter($response['content'], fn($message) => $message['type'] === 'tool_use');
        $this->assertNotEquals(0, $toolUse);
    }

    public function test_setTimeoutExists()
    {
        $timeoutSeconds = 10;
        $this->client->setTimeout($timeoutSeconds);

        $this->assertTrue(method_exists($this->client, 'setTimeout'), 'setTimeout method does not exist');
    }

    public function test_setTimeoutSetsCorrectValue()
    {
        $timeoutSeconds = 33;
        $this->client->setTimeout($timeoutSeconds);

        $reflection = new \ReflectionClass($this->client);
        $pendingRequestProperty = $reflection->getProperty('pendingRequest');
        $pendingRequestProperty->setAccessible(true);
        $pendingRequest = $pendingRequestProperty->getValue($this->client);

        $reflectionRequest = new \ReflectionClass($pendingRequest);
        $optionsProperty = $reflectionRequest->getProperty('options');
        $optionsProperty->setAccessible(true);

        $options = $optionsProperty->getValue($pendingRequest);

        $this->assertEquals($timeoutSeconds, $options['timeout'], 'The set timeout does not match the expected value');
    }
}
