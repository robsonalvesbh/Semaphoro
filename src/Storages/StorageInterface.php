<?php


namespace Semaphoro\Storages;


interface StorageInterface
{

    /**
     * @param string $key
     * @return array
     */
    public function getKeys(string $key): array;

    /**
     * @param string $key
     * @return bool|string
     */
    public function getValue(string $key): string;

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function save(string $key, string $value): bool;

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool;
}