<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ApplicationException;
use App\Core\AsController;
use App\Core\Cache;
use App\Core\Request;
use App\Core\Response;
use App\Core\Route;
use App\Services\MessageService;

#[AsController]
readonly class MessageController implements IController
{
    public function __construct(
        private MessageService $messageService,
        private Cache $cache
    ) {
    }

    /**
     * @throws ApplicationException
     */
    #[Route('/user-messages', methods: ['GET'])]
    public function getMessageAction(Request $request): Response
    {
        $userId = 1;
        $lastOrderedId = (int)$request->getParameter('last_id') ?? 0;
        $limit = (int)$request->getParameter('limit') ?? 1000;

        $cachedMessages = $this->cache->get($userId . $lastOrderedId . $limit) ?? '';

        $response = new Response($cachedMessages);

        if (!$cachedMessages) {
            $messages = $this->messageService->getUserMessagesListView($userId, $lastOrderedId, $limit);
            $this->cache->set($userId . $lastOrderedId . $limit, json_encode($messages));
            $response->json($messages);
        }

        return $response->json();
    }
}