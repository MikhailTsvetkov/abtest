<?php

namespace App\Database\Contracts;

use App\Database\Dto\DbResult;
use Closure;

interface Db
{
    public function exec(string $statement): int;

    public function execute(string $statement, array $params = []): DbResult;

    public function transaction(Closure $closure): mixed;
}
