<?php

declare(strict_types=1);

namespace App\Domain\Categories\Contracts;

use App\Database\Contracts\Db;
use App\Domain\Categories\Category;
use DateTimeImmutable;
use DateTimeZone;

final readonly class MysqlCategoriesStorage implements CategoriesStorage
{
    public function __construct(
        private Db $db,
    ) {
    }

    public function getPage(int $offset, int $limit, ?bool $isHasPosts = null): array
    {
        $statement = 'SELECT c.* FROM categories c ' . $this->articleFilter($isHasPosts)
            . ' ORDER BY c.id ASC LIMIT :limit OFFSET :offset';

        $result = $this->db->execute($statement, [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return $this->rowsToCategories($result->rows);
    }

    public function count(?bool $isHasPosts = null): int
    {
        $statement = 'SELECT COUNT(*) AS count FROM categories c ' . $this->articleFilter($isHasPosts);

        return (int) $this->db->execute($statement)->rows[0]['count'];
    }

    public function getById(int $id): ?Category
    {
        $rows = $this->db->execute('SELECT * FROM categories WHERE id = :id', ['id' => $id])->rows;

        return $this->rowsToCategories($rows)[0] ?? null;
    }

    public function getWithArticles(): array
    {
        return $this->rowsToCategories($this->db->execute(
            'SELECT c.* FROM categories c ' . $this->articleFilter(true) . ' ORDER BY c.id ASC',
        )->rows);
    }

    public function getForArticle(int $articleId): array
    {
        return $this->rowsToCategories($this->db->execute(
            'SELECT c.* FROM categories c INNER JOIN article_category ac ON ac.category_id = c.id '
            . 'WHERE ac.article_id = :article_id ORDER BY c.id ASC',
            ['article_id' => $articleId],
        )->rows);
    }

    private function articleFilter(?bool $isHasPosts): string
    {
        if ($isHasPosts === null) {
            return '';
        }

        return 'WHERE ' . ($isHasPosts ? '' : 'NOT ')
            . 'EXISTS (SELECT 1 FROM article_category ac WHERE ac.category_id = c.id)';
    }

    private function rowsToCategories(array $rows): array
    {
        $utc = new DateTimeZone('UTC');

        return array_map(fn(array $row): Category => new Category(
            (int) $row['id'],
            $row['name'],
            $row['description'] ?? '',
            (new DateTimeImmutable(
                $row['created_at'],
                $utc,
            ))->getTimestamp(),
            (new DateTimeImmutable(
                $row['updated_at'] ?? $row['created_at'],
                $utc,
            ))->getTimestamp(),
        ), $rows);
    }
}
