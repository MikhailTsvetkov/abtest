<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database\Enum\Direction;
use App\Domain\Articles\Contracts\ArticlesStorage;
use App\Domain\Articles\Enum\Sort;
use App\Domain\Categories\Contracts\CategoriesStorage;
use App\Http\HttpException;
use App\Http\ResponseInterface;
use App\Http\Request;
use App\View\PageRenderer;

final readonly class CategoryController
{
    public function __construct(
        private CategoriesStorage $categoriesStorage,
        private ArticlesStorage $articlesStorage,
        private PageRenderer $view,
    ) {}

    public function show(string $id): ResponseInterface
    {
        $category = $this->categoriesStorage->getById(Request::id($id));

        if ($category === null) {
            throw new HttpException(404, 'Категория не найдена.');
        }

        $page = Request::queryInt('page', 1);
        $perPage = Request::queryInt('per_page', 10, 100);
        $sort = Sort::tryFrom(Request::queryString('sort', 'date'));
        $direction = Direction::tryFrom(Request::queryString(
            'direction', 'desc',
        ));

        if (!isset($sort) || !isset($direction)) {
            throw new HttpException(
                400,
                'Допустимые значения: sort=date|views, direction=asc|desc.',
            );
        }

        $total = $this->articlesStorage->count($category->id);
        $totalPages = (int) ceil($total / $perPage);

        if ($page > max(1, $totalPages)) {
            throw new HttpException(404, 'Страница категории не найдена.');
        }

        $pageUrl = static fn (int $number): string => '/categories/' . $category->id . '?' . http_build_query([
            'sort' => $sort->value,
            'direction' => $direction->value,
            'page' => $number,
            'per_page' => $perPage,
        ]);

        return $this->view->page('category.tpl', [
            'category' => $category,
            'articles' => $this->articlesStorage->getPage(
                ($page - 1) * $perPage,
                $perPage,
                $direction,
                $category->id,
                $sort,
            ),
            'sort' => $sort->value,
            'direction' => $direction->value,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'previous_url' => $page > 1 ? $pageUrl($page - 1) : null,
                'next_url' => $page < $totalPages ? $pageUrl($page + 1) : null,
            ],
        ], $category->name);
    }
}
