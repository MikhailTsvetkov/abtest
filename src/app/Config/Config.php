<?php

namespace App\Config;

use Dotenv\Dotenv;
use RuntimeException;

final readonly class Config implements Contracts\Config
{
    public function get(string $key): mixed
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../../");
        $dotenv->load();

        $keys = explode('.', $key);
        $filename = array_shift($keys);
        $file = dirname(__DIR__, 2) . "/config/$filename.php";

        $config = include $file;

        while ($key = array_shift($keys)) {
            if (!array_key_exists($key, $config)) {
                throw new RuntimeException(
                    "Configuration key '$key' does not exist.",
                );
            }

            $config = $config[$key];
        }

        return $config;
    }
}
