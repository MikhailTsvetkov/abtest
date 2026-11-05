<?php

declare(strict_types=1);

namespace App\Repositories;

final readonly class UserRepository
{
    public function findUser(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'John Doe',
        ];
    }
}
