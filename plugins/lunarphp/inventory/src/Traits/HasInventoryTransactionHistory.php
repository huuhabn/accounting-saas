<?php

namespace HS\Inventory\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait InventoryTransactionHistoryTrait.
 */
trait HasInventoryTransactionHistory
{
    /*
     * Provides user identification to the model
     */
    use HasUser;

    /**
     * Make sure we try and assign the current user if enabled.
     */
    public static function bootInventoryTransactionHistoryTrait()
    {
        static::creating(function (Model $model) {
            $model->user_id = static::getCurrentUserId();
        });
    }

    /**
     * The belongsTo stock relationship.
     *
     * @return BelongsTo
     */
    abstract public function transaction();
}
