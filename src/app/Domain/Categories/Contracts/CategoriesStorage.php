<?php

namespace App\Domain\Categories\Contracts;

use App\Domain\Categories\Category;

interface CategoriesStorage
{
    /**
     * @return Category[]
     */
    public function getPage(int $offset, int $limit, ?bool $isHasPosts = null): array;

    public function count(?bool $isHasPosts = null): int;

    public function getById(int $id): ?Category;

    /**
     * @return Category[]
     */
    public function getWithArticles(): array;

    /**
     * @return Category[]
     */
    public function getForArticle(int $articleId): array;
}
