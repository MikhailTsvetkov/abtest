<?php

use App\Http\HttpException;
use App\Http\JsonResponse;

try {
    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/../src/routes/web.php';
} catch (HttpException $exception) {
    (new JsonResponse(['error' => $exception->getMessage()], $exception->status))->send();
} catch (Throwable $exception) {
    error_log((string) $exception);
    (new JsonResponse(['error' => 'Внутренняя ошибка сервера.'], 500))->send();
}
