<?php

declare(strict_types=1);

namespace App\Domain\Articles;

use App\Domain\Categories\Category;

final readonly class ArticleResponse
{
    public function __construct(
        public Article $article,
        /** @var Category[] */
        public array $categories,
        /** @var Article[] */
        public array $relatedArticles,
    ) {
    }
}
