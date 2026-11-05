<?php

declare(strict_types=1);

namespace App\View;

use App\Http\Request\Request;
use App\Http\Response\Contracts\ResponseInterface;
use App\Http\Response\HtmlResponse;
use App\Http\Response\JsonResponse;
use Smarty\Smarty;
use Throwable;

final readonly class PageRenderer
{
    public function __construct(private Smarty $smarty) {}

    public function page(string $template, array $data, string $title, int $status = 200): ResponseInterface
    {
        if (Request::wantsJson()) {
            return new JsonResponse($data, $status);
        }

        $view = $this->smarty->createTemplate($template);
        $view->assign($data);
        $view->assign('page_title', $title);

        return new HtmlResponse($view->fetch(), $status);
    }

    public function error(string $message, int $status): ResponseInterface
    {
        if (Request::wantsJson()) {
            return new JsonResponse(['error' => $message], $status);
        }

        try {
            return $this->page('error.tpl', ['error' => $message, 'status' => $status], 'Ошибка ' . $status, $status);
        } catch (Throwable $exception) {
            error_log((string) $exception);
            return self::fallbackError($message, $status);
        }
    }

    public static function fallbackError(string $message, int $status): ResponseInterface
    {
        if (Request::wantsJson()) {
            return new JsonResponse(['error' => $message], $status);
        }

        $message = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return new HtmlResponse(
            '<!doctype html><html lang="ru"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<link rel="icon" type="image/svg+xml" href="/favicon.svg">'
            . '<title>Ошибка ' . $status . '</title><link rel="stylesheet" href="/css/app.css"></head>'
            . '<body><main class="container error-page"><p class="eyebrow">Ошибка ' . $status . '</p>'
            . '<h1>' . $message . '</h1><a class="button" href="/">На главную</a></main></body></html>',
            $status,
        );
    }
}
