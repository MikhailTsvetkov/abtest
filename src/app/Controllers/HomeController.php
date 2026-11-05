<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Categories\Contracts\CategoriesStorage;
use App\Domain\Articles\Contracts\ArticlesStorage;
use App\Http\JsonResponse;

final readonly class HomeController
{
    public function __construct(
        private CategoriesStorage $categoriesStorage,
        private ArticlesStorage $articlesStorage,
    ) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoriesStorage->getWithArticles();
        $latest = $this->articlesStorage->getLatestForCategories(
            array_column($categories, 'id'),
        );
        $items = [];

        foreach ($categories as $category) {
            $items[] = [
                'category' => $category,
                'articles' => $latest[$category->id] ?? [],
                'all_articles_url' => '/categories/' . $category->id,
            ];
        }

        return new JsonResponse(['categories' => $items]);
    }
}
