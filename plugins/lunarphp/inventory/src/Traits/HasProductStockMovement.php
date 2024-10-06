<?php

namespace HS\Inventory\Traits;

use Illuminate\Database\Eloquent\Model;
use HS\Inventory\Exceptions\NoUserLoggedInException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait ProductStockMovementTrait.
 */
trait HasProductStockMovement
{
    use HasUser;
    use HasDbTransaction;

    /**
     * The belongsTo stock relationship.
     *
     * @return BelongsTo
     */
    abstract public function stock(): BelongsTo;

    /**
     * Rolls back the current movement.
     *
     * @param bool $recursive
     * @return mixed
     */
    public function rollback(bool $recursive = false): mixed
    {
        $stock = $this->stock;

        return $stock->rollback($this, $recursive);
    }
}
