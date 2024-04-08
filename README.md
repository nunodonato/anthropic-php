A simple PHP library to connect to Anthropic APIs (Claude).

I created this package to fulfull my own needs on my AI projects. I am aware that there are other similar packages, but either they didn't have all the required features or I just didn't like the implementation and usage.

### Features
* Completion API (legacy)
* Messages API
* Support multi-content messages (Vision)
* Tool usage (beta)
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
// or, for tool usage
$client = new Client($yourApiKey, useBeta: true);
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