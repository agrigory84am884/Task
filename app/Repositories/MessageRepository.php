<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\ApplicationException;
use App\Core\BaseRepository;
use App\Core\QueryBuilder;
use App\Entities\Message;
use PDO;

class MessageRepository extends BaseRepository
{

    public function __construct(private readonly QueryBuilder $queryBuilder)
    {
    }

    /**
     * @param $userId
     * @param $orderedId
     * @param $limit
     * @return array<Message>
     * @throws ApplicationException
     */
    public function getUserMessages($userId, $orderedId, $limit): array
    {
        $builder = $this->queryBuilder->select(['message_id', 'message_text', 'ordered_id'])
            ->from('Messages')
            ->where('user_id', '=', $userId)
            ->where('ordered_id','>', $orderedId, 'AND') //to make pagination faster I added an increment id and using that instead of offset
            ->limit($limit);

        $queryResult = $this->setBuilder($builder)->all();

        $messages = [];
        while ($row = $queryResult->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = $this->messageFactory($row);
        }
        return $messages;
    }

    protected function messageFactory($row): Message
    {
        return new Message($row['message_id']??'', $row['user_id']??0, $row['message_text']??'', $row['ordered_id']??0);
    }
}