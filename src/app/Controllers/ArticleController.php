<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Articles\Contracts\ArticlesStorage;
use App\Domain\Categories\Contracts\CategoriesStorage;
use App\Http\HttpException;
use App\Http\JsonResponse;
use App\Http\Request;

final readonly class ArticleController
{
    public function __construct(
        private ArticlesStorage $articlesStorage,
        private CategoriesStorage $categoriesStorage,
    ) {}

    public function show(string $id): JsonResponse
    {
        $article = $this->articlesStorage->getById(Request::id($id));

        if ($article === null) {
            throw new HttpException(404, 'Статья не найдена.');
        }

        return new JsonResponse([
            'article' => $article,
            'categories' => $this->categoriesStorage->getForArticle($article->id),
            'related_articles' => $this->articlesStorage->getRelated($article->id),
        ]);
    }
}
