<?php

declare(strict_types=1);

namespace App\Core;

use Predis\Client;

class Cache
{
    private Client $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT'],
        ]);

        $this->redis->select($_ENV['REDIS_DB'] ?? 0);
    }

    public function set($key, $value, $ttl = 3600): void
    {
        $this->redis->set($key, $value);
        $this->redis->expire($key, $ttl);
    }

    public function get($key): ?string
    {
        return $this->redis->get($key);
    }

    public function delete($key): void
    {
        $this->redis->del($key);
    }
}