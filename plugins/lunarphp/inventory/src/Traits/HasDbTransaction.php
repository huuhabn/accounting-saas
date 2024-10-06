<?php

namespace HS\Inventory\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

/**
 * Trait HasDbTransaction.
 */
trait HasDbTransaction
{
    /**
     * Alias for dispatch events easily that implement this trait.
     *
     * @param string $name
     * @param array $args
     * @return array|null
     */
    protected function fireEvent(string $name, array $args = []): ?array
    {
        return Event::dispatch( $name, $args);
    }

    /**
     * Start a new database transaction.
     *
     * @return void
     */
    protected function dbStartTransaction(): void
    {
        try {
            DB::beginTransaction();
        } catch (\Throwable $e) {
            $e->getMessage();
        }
    }

    /**
     * Commit the active database transaction.
     *
     * @return void
     */
    protected function dbCommitTransaction(): void
    {
        try {
            DB::commit();
        } catch (\Throwable $e) {
            $e->getMessage();
        }
    }


    /**
     * Rollback the active database transaction.
     *
     * @return void
     */
    protected function dbRollbackTransaction(): void
    {
        try {
            DB::rollback();
        } catch (\Throwable $e) {
            $e->getMessage();
        }
    }
}
