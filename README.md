# Запуск локальной среды разработки

```shell
cp .env.example .env
nano .env
```

```shell
docker compose up -d
```

Сервер запустится на `http::/localhost:3000`.

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
