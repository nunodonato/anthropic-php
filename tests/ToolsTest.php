<?php

namespace NunoDonato\AnthropicAPIPHP\Tests;

use Env\Dotenv;
use NunoDonato\AnthropicAPIPHP\Messages;
use NunoDonato\AnthropicAPIPHP\Tools;
use PHPUnit\Framework\TestCase;
use NunoDonato\AnthropicAPIPHP\Client;

class ToolsTest extends TestCase
{
    public function test_validates_tool_creation()
    {
        $tools = new Tools();
        $tools->addToolFromArray([
            'name' => 'test',
            'description' => 'test',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'test' => [
                        'type' => 'string',
                        'description' => 'test'
                    ]
                ],
                'required' => ['test']
            ]
        ]);

        $this->assertCount(1,$tools->tools());

        $this->expectExceptionMessage('Tool must have a description key');
        $tools->addToolFromArray([
            'name' => 'test',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'test' => [
                        'type' => 'string',
                        'description' => 'test'
                    ]
                ],
                'required' => ['test']
            ]
        ]);
        $this->assertCount(1,$tools->tools());
    }
}