<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

final readonly class UserController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function show(int $id): string
    {
        return '<pre>' .
            json_encode($this->userRepository->findUser($id), JSON_PRETTY_PRINT) .
            '<pre>';
    }
}