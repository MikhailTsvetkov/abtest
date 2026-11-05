<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateArticlesTable extends AbstractMigration
{
    public function change(): void
    {
        $articles = $this->table('articles');

        $articles
            ->addColumn(
                'image',
                'string',
                ['limit' => 255, 'null' => true],
            )
            ->addColumn(
                'title', 'string',
                ['limit' => 255, 'null' => false],
            )
            ->addColumn(
                'description', 'text',
                ['null' => true],
            )
            ->addColumn(
                'content', 'text',
                ['null' => false],
            )
            ->addColumn(
                'views_count', 'integer',
                ['default' => 0, 'signed' => false],
            )
            ->addTimestamps()
            ->create();
    }
}
