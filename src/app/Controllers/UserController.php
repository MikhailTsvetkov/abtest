<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

final readonly class UserController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function show(int $id): array
    {
        return $this->userRepository->findUser($id);
    }
}