<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    private string $content;
    private int $status;

    public function __construct(string $content = '', int $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
    }

    public function __toString()
    {
        return $this->content;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function json(array $data = [], int $status = 200): self
    {
        if($data){
            $this->content = json_encode($data);
        }

        $this->status = $status;
        header('Content-Type: application/json');
        return $this;
    }
}