<?php

declare(strict_types=1);

namespace App\Http;

final readonly class JsonResponse
{
    public function __construct(public array $data, public int $status = 200) {}

    public function send(): never
    {
        $json = json_encode(
            $this->data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
        );

        http_response_code($this->status);
        header('Content-Type: application/json; charset=utf-8');

        die($json);
    }
}
