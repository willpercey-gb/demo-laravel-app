<?php

namespace App\Util\Contracts;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;

trait HandlesTransactions
{
    private static DatabaseManager $manager;

    /**
     * @param Closure|null $transaction
     * @param int $attempts
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public static function transaction(?Closure $transaction = null, int $attempts = 1): mixed
    {
        if ($transaction) {
            return self::manager()->transaction($transaction, $attempts);
        }

        self::manager()->beginTransaction();

        return null;
    }

    public static function commit(): void
    {
        self::$manager->commit();
    }

    /**
     * @param int|null $toLevel
     *
     * @throws \Throwable
     */
    public static function rollback(?int $toLevel = null): void
    {
        self::$manager->rollBack($toLevel);
    }

    /**
     * @return DatabaseManager
     *
     * @throws BindingResolutionException
     */
    private static function manager(): DatabaseManager
    {
        if (!(self::$manager instanceof DatabaseManager)) {
            self::$manager = app()->make(DatabaseManager::class);
        }

        return self::$manager;
    }
}
