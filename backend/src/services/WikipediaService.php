<?php

namespace App\Services;

use App\Config\RedisConnection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;

class WikipediaService {
    private $client;
    private $redis;
    private $cachePrefix = 'wikipedia:';
    private $cacheTTL = 86400; // 24 hours

    public function __construct() {
        $this->client = new Client([
            'base_uri' => $_ENV['WIKIPEDIA_API_URL'],
            'timeout' => 5.0
        ]);
        $this->redis = RedisConnection::getInstance();
    }

    public function getSummary($title) {
        $cacheKey = $this->cachePrefix . urlencode($title);
        
        $cached = $this->redis->get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $response = $this->client->get($title);
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['extract'])) {
                $this->redis->set($cacheKey, $data['extract'], $this->cacheTTL);
                return $data['extract'];
            }
            
            throw new \Exception('No summary found in Wikipedia response');
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new \Exception('Wikipedia page not found');
            }
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception('Failed to fetch Wikipedia summary: ' . $e->getMessage());
        }
    }

    public function clearSummary($title = null) {
        if ($title) {
            $this->redis->delete($this->cachePrefix . urlencode($title));
        } else {
            $keys = $this->redis->getConnection()->keys($this->cachePrefix . '*');
            foreach ($keys as $key) {
                $this->redis->delete($key);
            }
        }
    }
} 