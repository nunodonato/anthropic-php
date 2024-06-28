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

    public function test_joins_continuous_messages_with_the_same_role()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, 'Hello');
        $messages->addMessage(Messages::ROLE_USER, 'How are you?');
        $messages->addMessage(Messages::ROLE_ASSISTANT, 'I am fine');
        $result = $messages->messages();
        $this->assertCount(2, $result);
        $this->assertCount(2, $result[0]['content']);
        $this->assertCount(1, $result[1]['content']);
    }
}