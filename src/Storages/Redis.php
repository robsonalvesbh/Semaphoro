<?php

namespace Semaphoro\Storages;


use Predis\Client;

class Redis implements StorageInterface
{
    /**
     * @var \Predis\Client
     */
    private $redisClient;

    /**
     * @var string
     */
    private $prefixKey;

    /**
     * Redis constructor.
     *
     * @param Client $redis The redis instance
     * @param string $prefix
     */
    public function __construct(Client $redis, string $prefix = 'semaphoro')
    {
        $this->redisClient = $redis;
        $this->prefixKey = $prefix;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getKeys(string $key): array
    {
        $keys = $this->redisClient->keys($this->addPrefix($key));

        return array_filter($keys, function ($key) {
            return $this->removePrefix($key);
        });
    }

    /**
     * @param string $key
     * @return string
     */
    public function getValue(string $key): string
    {
        return $this->redisClient->get($this->addPrefix($key));
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function save(string $key, string $value): bool
    {
        return (bool)$this->redisClient->set(
            $this->addPrefix($key),
            $value
        );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool
    {
        return (bool)$this->redisClient->del([$this->addPrefix($key)]);
    }


    /**
     * @param string $key
     * @return string
     */
    private function removePrefix(string $key): string
    {
        return str_replace(
            sprintf('%s:', $this->prefixKey),
            '',
            $key
        );
    }

    /**
     * @param string $key
     * @return string
     */
    private function addPrefix(string $key): string
    {
        return sprintf('%s:%s', $this->prefixKey, $key);
    }
}