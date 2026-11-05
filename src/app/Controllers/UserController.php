<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database\Contracts\Db;
use App\Repositories\UserRepository;

final readonly class UserController
{
    public function __construct(
        private UserRepository $userRepository,
        private Db $db,
    ) {}

    public function show(int $id): string
    {
        $t = $this->db->execute('show tables');
        return '<pre>' .
            json_encode($this->userRepository->findUser($id), JSON_PRETTY_PRINT) .
            '<pre>';
    }
}