<?php

namespace NunoDonato\AnthropicAPIPHP\Tests;

use Env\Dotenv;
use NunoDonato\AnthropicAPIPHP\Messages;
use NunoDonato\AnthropicAPIPHP\Tools;
use PHPUnit\Framework\TestCase;
use NunoDonato\AnthropicAPIPHP\Client;

class MessagesTest extends TestCase
{
    public function test_can_add_simple_text_message()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, 'Hello');
        $this->assertCount(1, $messages->messages());
    }

    public function test_can_add_object_text_message()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, [[
            'type' => 'text',
            'text' => 'Hello',
        ]]);
        $this->assertCount(1, $messages->messages());
    }

    public function test_prevents_non_alternated_roles()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, 'Hello');
        $this->expectExceptionMessage('Roles must alternate between "user" and "assistant".');
        $messages->addMessage(Messages::ROLE_USER, 'How are you?');
    }
}