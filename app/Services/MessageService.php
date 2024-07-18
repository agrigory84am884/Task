<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Message;
use App\Repositories\MessageRepository;

class MessageService
{
    public function __construct(private MessageRepository $messageRepository)
    {
    }

    /**
     * @param int $userId
     * @param int $lastOrderId
     * @param $limit
     * @return array<Message>
     */
    public function getUserMesagesListView(int $userId, int $lastOrderId, int $limit = 1000): array
    {
        return $this->messageRepository->getUserMessages($userId, $lastOrderId, $limit);
    }
}