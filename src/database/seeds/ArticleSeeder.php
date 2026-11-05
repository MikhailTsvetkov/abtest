<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class ArticleSeeder extends AbstractSeed
{
    private const TOPICS = [
        'Технологии' => [
            'Как навести порядок в цифровых заметках',
            'Умный дом: с чего начать знакомство',
            'Резервные копии семейного фотоархива',
            'Полезные сочетания клавиш на каждый день',
            'Как организовать рабочее место за компьютером',
        ],
        'Путешествия' => [
            'Выходные в незнакомом городе без спешки',
            'Что взять в короткую поездку с рюкзаком',
            'Как составить маршрут для пешей прогулки',
            'Путевой дневник: сохраняем впечатления',
            'Поездка на электричке как маленькое приключение',
        ],
        'Кулинария' => [
            'Пять идей завтрака из привычных продуктов',
            'Сезонные овощи: готовим простой ужин',
            'Домашний хлеб для первого кулинарного опыта',
            'Как собрать коллекцию любимых рецептов',
            'Тёплые напитки для прохладного вечера',
        ],
        'Здоровье' => [
            'Вечерние привычки для спокойного отдыха',
            'Как находить время для прогулок в будни',
            'Перерывы в работе: учимся переключать внимание',
            'Дневник самочувствия без сложных правил',
            'Выходной без расписания и лишней суеты',
        ],
        'Спорт' => [
            'Как выбрать приятный маршрут для пробежки',
            'Велопрогулка по городу: планируем выходной',
            'Домашние тренировки и маленькие регулярные шаги',
            'Командные игры для встречи с друзьями',
            'Как вести дневник спортивных впечатлений',
        ],
        'Культура' => [
            'Книжный клуб: первая встреча с друзьями',
            'Как смотреть картины без учебника искусствоведения',
            'Домашний киноклуб на один вечер',
            'Музыкальная прогулка по любимому городу',
            'Личный читательский дневник: заметки на полях',
        ],
        'Наука' => [
            'Почему небо меняет цвет на закате',
            'Наблюдаем за Луной в течение месяца',
            'Как устроены привычные измерительные приборы',
            'Погода за окном: начинаем дневник наблюдений',
            'Вопросы к научной новости перед чтением',
        ],
        'Природа' => [
            'Птицы городского парка: учимся замечать детали',
            'Лесная прогулка без следов после себя',
            'Маленький сад на подоконнике',
            'Фотодневник смены времён года',
            'Как устроить спокойный пикник на природе',
        ],
        'Образование' => [
            'Как составить личный план обучения на месяц',
            'Конспект, к которому хочется возвращаться',
            'Изучение языка через повседневные занятия',
            'Первый небольшой проект для нового навыка',
            'Как делиться знаниями и учиться вместе',
        ],
        'Дом и уют' => [
            'Уютный уголок для чтения в небольшой комнате',
            'Порядок на полках без большой уборки',
            'Освещение для разных домашних занятий',
            'Как хранить памятные вещи и фотографии',
            'Небольшие перемены в доме к новому сезону',
        ],
    ];

    public function getDependencies(): array
    {
        return [CategorySeeder::class];
    }

    public function run(): void
    {
        $imageDirectory = dirname(__DIR__, 3) . '/public/images';
        $images = glob($imageDirectory . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
        $images = array_values(array_filter($images, 'is_file'));

        if ($images === []) {
            throw new RuntimeException('No article images found in public/images.');
        }

        $categories = array_column($this->fetchAll('SELECT id, name FROM categories'), 'id', 'name');

        foreach (array_keys(self::TOPICS) as $name) {
            if (!isset($categories[$name])) {
                throw new RuntimeException('Run CategorySeeder before ArticleSeeder: missing category ' . $name);
            }
        }

        $categoryIds = array_map(
            static fn (string $name): int => (int) $categories[$name],
            array_keys(self::TOPICS),
        );
        $existingTitles = array_column($this->fetchAll('SELECT title FROM articles'), 'title');
        $now = new DateTimeImmutable();
        $index = 0;

        foreach (self::TOPICS as $categoryName => $titles) {
            foreach ($titles as $title) {
                $publishedAt = $now->modify('-' . (50 - $index) . ' days')->format('Y-m-d H:i:s');
                $hasSecondCategory = $index++ % 3 === 0;

                if (in_array($title, $existingTitles, true)) {
                    continue;
                }

                $description = 'Разбираем тему «' . $title . '»: идеи для первого знакомства, '
                    . 'личных наблюдений и небольших экспериментов.';
                $content = $description . "\n\n"
                    . CategorySeeder::CATEGORIES[$categoryName] . ' Начните с одного вопроса, который '
                    . 'вам интересен, и выделите время, чтобы спокойно разобраться в деталях.' . "\n\n"
                    . 'Подготовьте короткий список идей и выберите одну для практики. Не стремитесь '
                    . 'сразу охватить всё: небольшой понятный шаг помогает увидеть результат '
                    . 'и решить, что стоит попробовать дальше.' . "\n\n"
                    . 'Сохраните заметки о своём опыте: что оказалось полезным, что удивило '
                    . 'и какие вопросы остались. К этим наблюдениям удобно вернуться через неделю '
                    . 'или обсудить их с друзьями.';

                $this->table('articles')->insert([
                    'image' => '/images/' . basename($images[random_int(0, count($images) - 1)]),
                    'title' => $title,
                    'description' => $description,
                    'content' => $content,
                    'views_count' => random_int(0, 5000),
                    'created_at' => $publishedAt,
                    'updated_at' => $publishedAt,
                ])->saveData();

                $articleId = (int) $this->query('SELECT LAST_INSERT_ID()')->fetchColumn();
                $primaryCategoryId = (int) $categories[$categoryName];
                $links = [['article_id' => $articleId, 'category_id' => $primaryCategoryId]];

                if ($hasSecondCategory) {
                    $otherIds = array_values(array_diff($categoryIds, [$primaryCategoryId]));
                    $links[] = [
                        'article_id' => $articleId,
                        'category_id' => $otherIds[random_int(0, count($otherIds) - 1)],
                    ];
                }

                $this->table('article_category')->insert($links)->saveData();
            }
        }
    }
}
