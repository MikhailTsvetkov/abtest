<?php

namespace App\Domain\Categories;

final readonly class Category
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public int $createdTs,
        public int $updatedTs,
    ) {
    }
}
