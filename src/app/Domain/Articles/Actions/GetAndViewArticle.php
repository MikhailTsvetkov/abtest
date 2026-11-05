<?php

declare(strict_types=1);

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\Article;
use App\Domain\Articles\Contracts\ArticlesStorage;
use App\Domain\Articles\Exceptions\ArticleNotFoundException;
use App\Http\Exceptions\HttpException;

final readonly class GetAndViewArticle
{
    public function __construct(
        private ArticlesStorage $articlesStorage,
    ) {
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function exec(int $id): Article
    {
        // Todo: Тут надо бы переделать так, чтобы инкремент и получение записи выполнялось одним запросом (incrementViewsAndGet($id))
        $article = $this->articlesStorage->getById($id);

        if ($article === null) {
            throw new ArticleNotFoundException($id);
        }

        $this->articlesStorage->incrementViews($article->id);

        return $this->articlesStorage->getById($article->id)
            ?? throw new HttpException(404, 'Статья не найдена.');
    }
}
