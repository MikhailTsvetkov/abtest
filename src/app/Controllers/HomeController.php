<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Categories\Contracts\CategoriesStorage;
use App\Domain\Articles\Contracts\ArticlesStorage;
use App\Http\ResponseInterface;
use App\View\PageRenderer;

final readonly class HomeController
{
    public function __construct(
        private CategoriesStorage $categoriesStorage,
        private ArticlesStorage $articlesStorage,
        private PageRenderer $view,
    ) {}

    public function index(): ResponseInterface
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

        return $this->view->page('home.tpl', ['categories' => $items], 'Главная');
    }
}
