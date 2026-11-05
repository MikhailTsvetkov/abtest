<?php

namespace App\Database;

use App\Database\Dto\DbResult;
use Closure;
use PDO;
use PDOException;

final readonly class Db implements Contracts\Db
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    /**
     * @throws PDOException
     */
    public function exec(string $statement): int
    {
        return $this->pdo->exec($statement);
    }

    /**
     * @throws PDOException
     */
    public function execute(string $statement, array $params = []): DbResult
    {
        $pdoStatement = $this->pdo->prepare($statement);
        foreach ($params as $name => $value) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                $value === null => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
            $pdoStatement->bindValue(is_int($name) ? $name + 1 : ':' . ltrim($name, ':'), $value, $type);
        }
        $pdoStatement->execute();

        return new DbResult(
            $pdoStatement->rowCount(),
            $pdoStatement->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function transaction(Closure $closure): mixed
    {
        try {
            $this->pdo->beginTransaction();
            $transactionStarted = true;

            $result = $closure();

            $this->pdo->commit();

            $committed = true;
        } finally {
            if (
                isset($transactionStarted)
                && !isset($committed)
                && $this->pdo->inTransaction()
            ) {
                $this->pdo->rollBack();
            }
        }

        return $result;
    }
}
