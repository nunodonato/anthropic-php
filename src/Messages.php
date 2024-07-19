<?php

namespace NunoDonato\AnthropicAPIPHP;

class Messages
{
    const ROLE_USER = 'user';
    const ROLE_ASSISTANT = 'assistant';

    /** @var array<int, array<mixed, mixed>> */
    private array $messages = [];


    /**
     * @return array<int, array<mixed, mixed>>
     */
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
        $message = [$imageMessage];

        if ($text) {
            $textMessage = [
                'role' => self::ROLE_USER,
                'content' => $text
            ];
            $message[] = $textMessage;
        }

        $this->messages[] = [
            'role' => self::ROLE_USER,
            'content' => $message,
        ];

        return $this;
    }

    public function addUserImageMessageFromBase64(string $base64Encoded, string $mimeType, ?string $text = null): self
    {
        $imageMessage = [
            'type' => 'image',
            'source' => [
                'type' => 'base64',
                'data' => $base64Encoded,
                'media_type' => $mimeType
            ],
        ];
        $message = [$imageMessage];

        if ($text) {
            $textMessage = [
                'role' => self::ROLE_USER,
                'content' => $text
            ];
            $message[] = $textMessage;
        }

        $this->messages[] = [
            'role' => self::ROLE_USER,
            'content' => $message,
        ];

        return $this;
    }

    public function addAssistantTextMessage(string $text): self
    {
        return $this->addMessage(self::ROLE_ASSISTANT, $text);
    }

    /**
     * @param string|array<int, array<string, string>> $content
     */
    public function addMessage(string $role, string|array $content): self
    {
        if (!in_array($role, [self::ROLE_USER, self::ROLE_ASSISTANT])) {
            throw new \InvalidArgumentException('Invalid role');
        }

        if (is_array($content)) {
            foreach ($content as $i => $block) {
                if (!is_array($block)) {
                    throw new \InvalidArgumentException('Block must be an array. Index: ' . $i);
                }
                if (!array_key_exists('type', $block)) {
                    throw new \InvalidArgumentException('Block content type is required. Index: ' . $i);
                }
                if ($block['type'] == 'text' && !array_key_exists('text', $block)) {
                    throw new \InvalidArgumentException('Block text property is required for text type. Index: ' . $i);
                }
                if ($block['type'] == 'image' && (!array_key_exists('source', $block) || !array_key_exists(
                            'data',
                            $block['source']
                        ) || !array_key_exists('media_type', $block['source']))) {
                    throw new \InvalidArgumentException(
                        'source.media_type and source.data properties are required for image type. Index: ' . $i
                    );
                }
            }
        } else {
            // let's standardize the way the blocks are passed
            $content = [
                [
                    'type' => 'text',
                    'text' => $content
                ]
            ];
        }

        if (count($this->messages) > 0) {
            $lastMessage = $this->messages[count($this->messages) - 1];
            if ($lastMessage['role'] === $role) {
                // merge messages of the same role
                $this->messages[count($this->messages) - 1]['content'] = array_merge($lastMessage['content'], $content);
                return $this;
            }
        }


        $this->messages[] = [
            'role' => $role,
            'content' => $content,
        ];

        return $this;
    }
}