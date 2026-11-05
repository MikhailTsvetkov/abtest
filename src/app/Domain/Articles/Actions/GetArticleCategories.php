<?php

declare(strict_types=1);

namespace App\Domain\Articles\Actions;

use App\Domain\Categories\Contracts\CategoriesStorage;

final readonly class GetArticleCategories
{
    public function __construct(
        private CategoriesStorage $categoriesStorage,
    ) {
    }

    public function exec(int $id)
    {
        return $this->categoriesStorage->getForArticle($id);
    }
}
