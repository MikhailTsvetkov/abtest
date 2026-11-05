<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Articles\Exceptions\ArticleNotFoundException;
use App\Domain\Articles\Factories\ArticleResponseFactory;
use App\Http\Exceptions\HttpException;
use App\Http\Request\Request;
use App\Http\Response\Contracts\ResponseInterface;
use App\View\PageRenderer;

final readonly class ArticleController
{
    public function __construct(
        private ArticleResponseFactory $responseFactory,
        private PageRenderer $view,
    ) {}

    public function show(string $id): ResponseInterface
    {
        try {
            $response = $this->responseFactory->create(Request::id($id));
        } catch (ArticleNotFoundException) {
            throw new HttpException(404, 'Статья не найдена.');
        }

        // Todo: здесь нужно возвращать использовать не PageRenderer, а ArticleResponse->render(), например
        return $this->view->page('article.tpl', [
            'article' => $response->article,
            'categories' => $response->categories,
            'related_articles' => $response->relatedArticles,
        ], $response->article->title);
    }
}
