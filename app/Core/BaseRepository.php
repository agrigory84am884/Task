<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\DB;
use PDOStatement;

abstract class BaseRepository
{
    /**
     * @var array
     */
    private array $params;
    private string $query;

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setBuilder(QueryBuilder $builder): self
    {
        $this->query = $builder->createQuery();
        $this->params = $builder->getParams();
        return $this;
    }

    public function setQuery(string $sql): self
    {
        $this->query = $sql;
        return $this;
    }

    /**
     * @throws ApplicationException
     */
    public function all(): PDOStatement
    {
        try {
            return DB::getQueryStatement($this->query, $this->params);
        }catch (UnexpectedValueException $e){
            throw new ApplicationException("BaseRepository::all | ErrorMessages: " . $e->getMessage());
        }

    }

    public function setParam(string $kel, mixed $value): self
    {
        $this->params[':' . $kel] = $value;
        return $this;
    }
}