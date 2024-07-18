<?php

namespace App\Core;

class QueryBuilder
{
    private array $queryBuilder;
    private string $sql;
    private array $params;

    public function where(string $field, string $operator, mixed $value, string $andOr = ''): self
    {
        $this->params[':' . $field] = $value;
        $this->queryBuilder['where'][] = $andOr . ' ' . $field . $operator . ':' . $field . ' ';
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->queryBuilder['limit'] = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->queryBuilder['offset'] = $offset;
        return $this;
    }

    public function select(array $field): self
    {
        $this->queryBuilder['select'] = $field;
        return $this;
    }

    public function from(string $table): self
    {
        $this->queryBuilder['from'] = $table;
        return $this;
    }

    public function groupBy(string $field): self
    {
        $this->queryBuilder['group_by'] = $field;
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->queryBuilder['order_by'][$field] = $direction;
        return $this;
    }

    public function createQuery(): string
    {
        $this->processSelect();
        $this->processFrom();
        $this->processWhere();
        $this->processGroupBy();
        $this->processOrderBy();
        $this->processOffset();
        $this->processLimit();
        return $this->sql;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    private function processSelect(): void
    {
        $this->sql = "SELECT " .
            (isset($this->queryBuilder['select']) ? implode(", ", $this->queryBuilder['select']) : ' * ');
    }

    private function processFrom(): void
    {
        if (!isset($this->queryBuilder['from'])) {
            throw new Exception('DB table not found');
        }

        $this->sql .= " FROM " . $this->queryBuilder['from'];
    }

    private function processWhere(): void
    {
        $this->sql .= isset($this->queryBuilder['where']) ? " WHERE " . implode($this->queryBuilder['where']) : '';
    }

    private function processLimit(): void
    {
        $this->sql .= isset($this->queryBuilder['limit']) ? " LIMIT " . $this->queryBuilder['limit'] : '';
    }

    public function processOffset(): void
    {
        $this->sql .= isset($this->queryBuilder['offset']) ? " OFFSET " . $this->queryBuilder['offset'] : '';
    }

    public function processGroupBy(): void
    {
        $this->sql .= isset($this->queryBuilder['group_by']) ? " GROUP BY " . $this->queryBuilder['group_by'] : '';
    }

    public function processOrderBy(): void
    {
        $this->sql .= isset($this->queryBuilder['order_by']) ? " ORDER BY " . $this->queryBuilder['order_by'] : '';
    }
}