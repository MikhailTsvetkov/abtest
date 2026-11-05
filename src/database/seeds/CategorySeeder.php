<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class CategorySeeder extends AbstractSeed
{
    public const CATEGORIES = [
        'Технологии' => 'Цифровые инструменты, полезные устройства и привычки, которые помогают в повседневной жизни.',
        'Путешествия' => 'Идеи коротких поездок, прогулок и самостоятельных маршрутов для новых впечатлений.',
        'Кулинария' => 'Простые рецепты, сезонные продукты и небольшие хитрости домашней кухни.',
        'Здоровье' => 'Повседневные привычки для отдыха, хорошего самочувствия и заботы о себе.',
        'Спорт' => 'Движение для удовольствия: прогулки, тренировки и активные выходные.',
        'Культура' => 'Книги, кино, музыка и искусство, которые делают обычные дни интереснее.',
        'Наука' => 'Наблюдения, открытия и понятные объяснения явлений окружающего мира.',
        'Природа' => 'Неспешные прогулки, наблюдение за животными и бережное отношение к окружающей среде.',
        'Образование' => 'Самостоятельное обучение, новые навыки и способы организовать работу с информацией.',
        'Дом и уют' => 'Удобное пространство, порядок и небольшие изменения, которые делают дом уютнее.',
    ];

    public function run(): void
    {
        $existingNames = array_column($this->fetchAll('SELECT name FROM categories'), 'name');
        $createdAt = (new DateTimeImmutable('-60 days'))->format('Y-m-d H:i:s');
        $rows = [];

        foreach (self::CATEGORIES as $name => $description) {
            if (in_array($name, $existingNames, true)) {
                continue;
            }

            $rows[] = [
                'name' => $name,
                'description' => $description,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        if ($rows !== []) {
            $this->table('categories')->insert($rows)->saveData();
        }
    }
}
