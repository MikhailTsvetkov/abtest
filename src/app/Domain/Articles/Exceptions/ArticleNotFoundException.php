<?php

declare(strict_types=1);

namespace App\Domain\Articles\Exceptions;

use Exception;

final class ArticleNotFoundException extends Exception
{
    public function __construct(int $id) {
        parent::__construct("Article ID $id not found");
    }
}
