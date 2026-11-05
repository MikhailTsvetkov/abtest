<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Http\Response\Contracts\ResponseInterface;

final readonly class HtmlResponse implements ResponseInterface
{
    public function __construct(public string $html, public int $status = 200) {}

    public function send(): never
    {
        http_response_code($this->status);
        header('Content-Type: text/html; charset=utf-8');
        header('Vary: Accept');
        die($this->html);
    }
}
