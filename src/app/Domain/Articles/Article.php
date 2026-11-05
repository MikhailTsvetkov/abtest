<?php

namespace App\Domain\Articles;

final readonly class Article
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $content,
        public ?string $image,
        public int $viewsCount,
        public int $createdTs,
        public int $updatedTs,
    ) {
    }
}
