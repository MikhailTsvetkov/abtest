<?php

namespace App\Database\Dto;

final readonly class DbResult
{
    public function __construct(
        public int $rowCount,
        public array $rows,
    ) {
    }
}
