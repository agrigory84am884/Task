<?php

declare(strict_types=1);

namespace App\Core;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(public string $path, public array $methods)
    {
    }
}