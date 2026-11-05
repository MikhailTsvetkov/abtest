<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $categories = $this->table('categories');

        $categories
            ->addColumn(
            'name', 'string', ['limit' => 256, 'null' => false],
            )
            ->addColumn('description', 'text', ['null' => true])
            ->addTimestamps()
            ->create();

        $categories->addIndex(['name'], ['unique' => true])->update();
    }
}
