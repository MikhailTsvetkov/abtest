<?php

declare(strict_types=1);

namespace App\Domain\Articles\Factories;

use App\Domain\Articles\Actions\GetAndViewArticle;
use App\Domain\Articles\Actions\GetArticleCategories;
use App\Domain\Articles\Actions\GetRelatedArticles;
use App\Domain\Articles\ArticleResponse;
use App\Domain\Articles\Exceptions\ArticleNotFoundException;

final readonly class ArticleResponseFactory
{
    public function __construct(
        private GetAndViewArticle $getAndViewArticle,
        private GetArticleCategories $getArticleCategories,
        private GetRelatedArticles $getRelatedArticles,
    ) {
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function create(int $id): ArticleResponse
    {
        return new ArticleResponse(
            $this->getAndViewArticle->exec($id),
            $this->getArticleCategories->exec($id),
            $this->getRelatedArticles->exec($id),
        );
    }
}
