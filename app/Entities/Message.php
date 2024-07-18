<?php

declare(strict_types=1);

namespace App\Entities;

readonly class Message
{
    public function __construct(
        public string $message_id,
        public int $user_id,
        public string $message_text,
        public int $ordered_id
    )
    {
    }

    public function getMessageId(): string
    {
        return $this->message_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getMessageText(): string
    {
        return $this->message_text;
    }

    public function getOrderedId(): int
    {
        return $this->ordered_id;
    }
}