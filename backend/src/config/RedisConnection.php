<?php

namespace App\Config;

use Redis;
use RedisException;

class RedisConnection {
    private static $instance = null;
    private $redis;

    private function __construct() {
        try {
            $this->redis = new Redis();
            $this->redis->connect(
                $_ENV['REDIS_HOST'],
                $_ENV['REDIS_PORT'] ?? 6379
            );
        } catch(RedisException $e) {
            throw new RedisException($e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->redis;
    }

    public function get($key) {
        return $this->redis->get($key);
    }

    public function set($key, $value, $ttl = null) {
        if ($ttl) {
            return $this->redis->setex($key, $ttl, $value);
        }
        return $this->redis->set($key, $value);
    }

    public function delete($key) {
        return $this->redis->del($key);
    }
} 