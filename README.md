A simple PHP library to connect to Anthropic APIs (Claude).

I created this package to fulfill my own needs on my AI projects. I am aware that there are other similar packages, but either they didn't have all the required features or I just didn't like the implementation and usage.

### Features
* Messages API
* Support multi-content messages (Vision)
* Tool usage (_beta_)
* Completion API (_legacy_)
* Prompt caching (system message only, for now)
* Helps you build and validate messages/tools data structures before sending to the API

#### Roadmap
* Streaming

### Installation
`composer require nunodonato/anthropic-php`

### Usage

More soon.

#### Instantiate the client

```php
use NunoDonato\AnthropicAPIPHP\Client;

// ...

$client = new Client($yourApiKey);
```

#### Messages API usage

```php
use NunoDonato\AnthropicAPIPHP\Client;
use NunoDonato\AnthropicAPIPHP\Messages;

// ...

$client = new Client($yourApiKey);
$messages = new Messages();
$messages->addUserTextMessage('Hello AI!');

$response = $this->client->messages(Client::MODEL_SONNET, $messages);

// you can chain messages
$messages->addUserTextMessage('Hello AI!')
        ->addAssistantTextMessage('Hello human!')
        ->addUserImageMessage('https://example.com/image.jpg', 'What do you see here?');

```

#### Available models
```php
const MODEL_OPUS = 'claude-3-opus-20240229';
    const MODEL_SONNET = 'claude-3-sonnet-20240229';
    const MODEL_HAIKU = 'claude-3-haiku-20240307';
    const MODEL_3_5_SONNET = 'claude-3-5-sonnet-latest';
    const MODEL_3_5_HAIKU = 'claude-3-5-haiku-latest';
    const MODEL_CLAUDE_2 = 'claude-2.1';
```
