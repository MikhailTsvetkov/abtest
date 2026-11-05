<?php

declare(strict_types=1);

namespace App\Http\Response\Contracts;

interface ResponseInterface
{
    public function send(): never;
}
