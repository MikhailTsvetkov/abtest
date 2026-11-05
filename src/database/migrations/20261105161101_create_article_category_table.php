<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateArticleCategoryTable extends AbstractMigration
{
    public function change(): void
    {
        $articleCategory = $this->table(
            'article_category',
            ['id' => false, 'primary_key' => ['article_id', 'category_id']],
        );

        $articleCategory
            ->addColumn(
                'article_id', 'integer',
                ['signed' => false, 'null' => false],
            )
            ->addColumn(
                'category_id', 'integer',
                ['signed' => false, 'null' => false],
            )
            ->addForeignKey(
                'article_id', 'articles', 'id',
                ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'],
            )
            ->addForeignKey(
                'category_id', 'categories', 'id',
                ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'],
            )
            ->create();
    }
}
