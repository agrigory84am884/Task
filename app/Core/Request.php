<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public function getBody(): array
    {
        $body = file_get_contents('php://input');
        return json_decode($body, true) ?? [];
    }

    public function getParameter(string $key)
    {
        return $_GET[$key] ?? null; // For GET parameters
    }
}