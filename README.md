# Запуск локальной среды разработки

```shell
cp .env.example .env
nano .env
```

```shell
docker compose up -d
```

Сервер запустится на `http://localhost:3000`.

## Composer
```shell
docker compose exec app composer install
```

## Xdebug
Для включения `Xdebug` (на примере PhpStorm):
**Settings** -> **PHP** -> **Servers** -> **Add**.

*Name* = `ab_server_mapping`
*Host* = `localhost`
*Port* = `3000`
*Absolute path* = `/var/www/html`

## Phinx

### Миграции

```shell
docker compose exec app composer phinx -- migrate
```

### Сиды

`CategorySeeder` добавляет 10 категорий, `ArticleSeeder` — 50 постов и связи с
категориями. Каждая категория получает минимум пять постов; часть постов относится
сразу к двум категориям. Изображения выбираются случайно из `public/images`
и сохраняются как URL `/images/...`. Даты публикации и просмотры различаются.

Запускайте оба сидера вместе: Phinx выполнит категории перед статьями. Повторный
запуск пропускает категории с существующими названиями и посты с существующими
заголовками, сохраняя текущие данные.

```shell
docker compose exec app composer phinx -- seed:run
```

### Команды для копипасты

```shell
docker compose exec app composer phinx -- status
docker compose exec app composer phinx -- rollback
docker compose exec app composer phinx -- create CreateCategoriesTable
docker compose exec app composer phinx -- create CreateArticlesTable
docker compose exec app composer phinx -- create CreateArticleCategoryTable
docker compose exec app composer phinx -- seed:create CategorySeeder
docker compose exec app composer phinx -- seed:create ArticleSeeder
```
