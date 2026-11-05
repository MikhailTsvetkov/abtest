<?php

declare(strict_types=1);

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\Article;
use App\Domain\Articles\Contracts\ArticlesStorage;

final readonly class GetRelatedArticles
{
    public function __construct(
        private ArticlesStorage $articlesStorage,
    ) {
    }

    /**
     * @return Article[]
     */
    public function exec(int $id): array
    {
        return $this->articlesStorage->getRelated($id);
    }
}
