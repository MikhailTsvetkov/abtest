<?php

namespace App\Domain\Articles\Contracts;

use App\Database\Enum\Direction;
use App\Domain\Articles\Article;
use App\Domain\Articles\Enum\Sort;

interface ArticlesStorage
{
    /**
     * @return Article[]
     */
    public function getPage(
        int $offset,
        int $limit,
        Direction $direction = Direction::DESC,
        ?int $categoryId = null,
        Sort $sort = Sort::DATE,
    ): array;

    public function count(?int $categoryId = null): int;

    public function getById(int $id): ?Article;

    /**
     * @return array<int, Article[]>
     */
    public function getLatestForCategories(array $categoryIds, int $limit = 3): array;

    /**
     * @return Article[]
     */
    public function getRelated(int $articleId, int $limit = 3): array;
}
