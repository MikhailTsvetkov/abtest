<?php

declare(strict_types=1);

use App\Config\Config;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$database = (new Config())->get('database');

return [
    'paths' => [
        'migrations' => dirname(__DIR__) . '/database/migrations',
        'seeds' => dirname(__DIR__) . '/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'development',
        'development' => [
            'adapter'   => 'mysql',
            'host'      => $database['host'],
            'name'      => $database['database'],
            'user'      => $database['username'],
            'pass'      => $database['password'],
            'port'      => (int) $database['port'],
            'charset'   => 'utf8mb4',
        ],
    ],
    'version_order' => 'creation',
];
