<?php

use App\Http\HttpException;
use App\View\PageRenderer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$renderer = null;

try {
    /** @var ContainerBuilder $container */
    require_once __DIR__ . '/../bootstrap.php';

    $renderer = $container->get(PageRenderer::class);

    require_once __DIR__ . '/../src/routes/web.php';
} catch (HttpException $exception) {
    (
        $renderer?->error($exception->getMessage(), $exception->status)
        ?? PageRenderer::fallbackError($exception->getMessage(), $exception->status)
    )->send();
} catch (Throwable $exception) {
    error_log((string) $exception);

    (
        $renderer?->error('Внутренняя ошибка сервера.', 500)
        ?? PageRenderer::fallbackError('Внутренняя ошибка сервера.', 500)
    )->send();
}
