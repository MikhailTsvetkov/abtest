<?php

declare(strict_types=1);

namespace App\Domain\Articles\Contracts;

use App\Database\Contracts\Db;
use App\Database\Enum\Direction;
use App\Domain\Articles\Article;
use App\Domain\Articles\Enum\Sort;
use DateTimeImmutable;
use DateTimeZone;

final readonly class MysqlArticlesStorage implements ArticlesStorage
{
    public function __construct(
        private Db $db,
    ) {
    }

    public function getPage(
        int $offset,
        int $limit,
        Direction $direction = Direction::DESC,
        ?int $categoryId = null,
        Sort $sort = Sort::DATE,
    ): array {
        $column = $sort === Sort::VIEWS ? 'a.views_count' : 'a.created_at';
        $order = strtoupper($direction->value);
        $statement = 'SELECT a.* FROM articles a ' . $this->categoryFilter($categoryId)
            . " ORDER BY $column $order, a.id $order LIMIT :limit OFFSET :offset";

        $params = [
            'offset' => $offset,
            'limit' => $limit,
        ];
        if ($categoryId !== null) {
            $params['category_id'] = $categoryId;
        }

        return $this->rowsToArticles($this->db->execute($statement, $params)->rows);
    }

    public function count(?int $categoryId = null): int
    {
        $statement = 'SELECT COUNT(*) AS count FROM articles a ' . $this->categoryFilter($categoryId);

        return (int) $this->db->execute(
            $statement,
            $categoryId === null ? [] : ['category_id' => $categoryId],
        )->rows[0]['count'];
    }

    public function getById(int $id): ?Article
    {
        $rows = $this->db->execute('SELECT * FROM articles WHERE id = :id', ['id' => $id])->rows;

        return $this->rowsToArticles($rows)[0] ?? null;
    }

    public function incrementViews(int $id): void
    {
        $this->db->execute(
            'UPDATE articles SET views_count = views_count + 1 WHERE id = :id',
            ['id' => $id],
        );
    }

    public function getLatestForCategories(array $categoryIds, int $limit = 3): array
    {
        if ($categoryIds === []) {
            return [];
        }

        $params = ['limit' => $limit];
        $placeholders = [];
        foreach (array_values(array_unique($categoryIds)) as $index => $id) {
            $placeholders[] = ':category_' . $index;
            $params['category_' . $index] = (int) $id;
        }
        $ids = implode(', ', $placeholders);
        $rows = $this->db->execute(
            'SELECT ranked.* FROM ('
            . 'SELECT a.*, ac.category_id, '
            . 'ROW_NUMBER() OVER (PARTITION BY ac.category_id ORDER BY a.created_at DESC, a.id DESC) AS position '
            . 'FROM articles a INNER JOIN article_category ac ON ac.article_id = a.id '
            . "WHERE ac.category_id IN ($ids)"
            . ') ranked WHERE ranked.position <= :limit ORDER BY ranked.category_id, ranked.position',
            $params,
        )->rows;

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['category_id']][] = $this->rowToArticle($row);
        }

        return $result;
    }

    public function getRelated(int $articleId, int $limit = 3): array
    {
        $categories = $this->db->execute(
            'SELECT category_id FROM article_category WHERE article_id = :article_id',
            ['article_id' => $articleId],
        )->rows;
        if ($categories === []) {
            return [];
        }

        $params = ['article_id' => $articleId, 'limit' => $limit];
        $placeholders = [];
        foreach ($categories as $index => $category) {
            $placeholders[] = ':category_' . $index;
            $params['category_' . $index] = (int) $category['category_id'];
        }
        $ids = implode(', ', $placeholders);

        return $this->rowsToArticles($this->db->execute(
            'SELECT a.* FROM articles a INNER JOIN ('
            . 'SELECT article_id, COUNT(*) AS shared_categories FROM article_category '
            . "WHERE category_id IN ($ids) AND article_id <> :article_id "
            . 'GROUP BY article_id'
            . ') related ON related.article_id = a.id '
            . 'ORDER BY related.shared_categories DESC, a.created_at DESC, a.id DESC LIMIT :limit',
            $params,
        )->rows);
    }

    private function categoryFilter(?int $categoryId): string
    {
        return $categoryId === null ? ''
            : 'WHERE EXISTS (SELECT 1 FROM article_category ac WHERE ac.article_id = a.id AND ac.category_id = :category_id)';
    }

    private function rowsToArticles(array $rows): array
    {
        return array_map($this->rowToArticle(...), $rows);
    }

    private function rowToArticle(array $row): Article
    {
        $utc = new DateTimeZone('UTC');

        return new Article(
            (int) $row['id'],
            $row['title'],
            $row['description'] ?? '',
            $row['content'],
            $row['image'],
            (int) $row['views_count'],
            (new DateTimeImmutable($row['created_at'], $utc))->getTimestamp(),
            (new DateTimeImmutable($row['updated_at'] ?? $row['created_at'], $utc))->getTimestamp(),
        );
    }
}
