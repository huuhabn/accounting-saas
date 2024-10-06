<?php

namespace HS\Inventory\Traits;

use Exception;
use HS\Inventory\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use HS\Inventory\Models\InventoryStock;
use HS\Inventory\Models\InventoryStockMovement;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use HS\Inventory\Exceptions\NotEnoughStockException;
use HS\Inventory\Exceptions\InvalidQuantityException;
use HS\Inventory\Exceptions\InvalidMovementException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait HasProductStock.
 */
trait HasInventoryStock
{
    /*
     * Verification helper functions
     */
    use VerifyTrait;

    /*
     * Helpers for starting database transactions
     */
    use HasDbTransaction;

    use HasUser;

    /**
     * Overrides the models boot function to set the user
     * ID automatically to every new record.
     */
    public static function bootHasInventoryStock(): void
    {
        static::creating(function (InventoryStock $stock) {
            $stock->user_id = static::getCurrentUserId();

            if (!$stock->reason) {
                $stock->reason = __('hs.inventory::reasons.first_record');
            }
        });

        static::created(function (InventoryStock $stock) {
            if (!$stock->getLastMovement()) {
                $stock->generateStockMovement(0, $stock->quantity, $stock->reason, $stock->cost);
            }
        });

        static::updating(function (InventoryStock $stock) {
            /**
             * Retrieve the original quantity before it was updated,
             * so we can create generate an update with it
             */
            $stock->beforeQuantity = $stock->getOriginal('available_stock');

            if (!$stock->reason) {
                $stock->reason = __('hs.inventory::reasons.change');
            }
        });

        static::updated(function (InventoryStock $stock){
            $stock->generateStockMovement($stock->beforeQuantity, $stock->available_stock, $stock->reason, $stock->cost);
        });

        static::deleting(function (self $record) {
            $this->dbStartTransaction();
            try{
                $record->purchases()->delete();
                $record->movements()->delete();
                $this->dbCommitTransaction();
            } catch (Exception $e) {
                $this->dbRollbackTransaction();
            }
        });
    }

    /**
     * Stores the quantity before an update.
     */
    private int $beforeQuantity = 0;

    /**
     * Returns the last movement on the current stock record.
     *
     * @return InventoryStockMovement|null
     */
    public function getLastMovement(): ?InventoryStockMovement
    {
        return $this->movements()->orderBy('created_at', 'DESC')->first();
    }

    /**
     * The hasMany movements relationship.
     *
     * @return HasMany
     */
    abstract public function movements(): HasMany;

    /**
     * Creates a new stock movement record.
     *
     * @param int $before
     * @param int $after
     * @param string $reason
     * @param int $cost
     * @return InventoryStockMovement
     */
    private function generateStockMovement(int $before, int $after, string $reason = '', int $cost = 0): InventoryStockMovement
    {
        return $this->movements()->create([
            'stock_id' => $this->getKey(),
            'before' => $before,
            'after' => $after,
            'reason' => $reason,
            'cost' => $cost,
        ]);
    }

    /**
     * The BelongsTo warehouse relationship.
     *
     * @return BelongsTo
     */
    abstract public function warehouse(): BelongsTo;

    /**
     * The belongsTo item relationship.
     *
     * @return BelongsTo
     */
    abstract public function item(): BelongsTo;

    /**
     * Performs a quantity update. Automatically determining
     * depending on the quantity entered if stock is being taken
     * or added.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     * @return InventoryStock|bool
     * @throws InvalidQuantityException
     * @throws NotEnoughStockException
     */
    public function updateQuantity(int $quantity, string $reason = '', int $cost = 0)
    {
        if ($this->isValidQuantity($quantity)) {
            return $this->processUpdateQuantityOperation($quantity, $reason, $cost);
        }

        return false;
    }

    /**
     * Processes a quantity update operation.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     * @return bool|InventoryStock
     * @throws InvalidQuantityException
     * @throws NotEnoughStockException
     * @throws \Throwable
     */
    private function processUpdateQuantityOperation(int $quantity, string $reason = '', int $cost = 0): InventoryStock|bool
    {
        if ($quantity > $this->available_stock) {
            $putting = $quantity - $this->available_stock;

            return $this->put($putting, $reason, $cost);
        } else {
            $taking = $this->available_stock - $quantity;

            return $this->take($taking, $reason, $cost);
        }
    }

    /**
     * Processes a 'put' operation on the current stock.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     * @return bool|InventoryStock
     * @throws InvalidQuantityException
     * @throws \Throwable
     */
    public function put(int $quantity, string $reason = '', int $cost = 0): InventoryStock|bool
    {
        if ($this->isValidQuantity($quantity)) {
            return $this->processPutOperation($quantity, $reason, $cost);
        }

        return false;
    }

    /**
     * Processes adding quantity to current stock.
     *
     * @param int $putting
     * @param string $reason
     * @param int $cost
     * @return InventoryStock|false
     * @throws \Throwable
     */
    private function processPutOperation(int $putting, string $reason = '', int $cost = 0): false|InventoryStock
    {
        $before = $this->available_stock;

        $total = $putting + $before;

        /*
         * If the updated total and the beginning total are the same,
         * we'll check if duplicate movements are allowed
         */
        if ($total == $this->available_stock && !$this->allowDuplicateMovementsEnabled()) {
            return $this;
        }

        $this->available_stock = $total;
        $this->reason = $reason;
        $this->cost = $cost;

        $this->dbStartTransaction();

        try {
            if ($this->save()) {
                $this->dbCommitTransaction();

                $this->fireEvent('inventory.stock.added', [
                    'stock' => $this,
                ]);

                return $this;
            }
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * Returns true/false from the configuration file determining
     * whether stock movements can have the same before and after
     * quantities.
     *
     * @return bool
     */
    private function allowDuplicateMovementsEnabled(): bool
    {
        return config('hs.inventory.allow_duplicate_movements', false);
    }

    /**
     * Processes a 'take' operation on the current stock.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     * @return false|InventoryStock
     * @throws InvalidQuantityException
     * @throws NotEnoughStockException
     * @throws \Throwable
     */
    public function take(int $quantity, string $reason = '', int $cost = 0): false|InventoryStock
    {
        if ($this->isValidQuantity($quantity) && $this->hasEnoughStock($quantity)) {
            return $this->processTakeOperation($quantity, $reason, $cost);
        }
        return false;
    }

    /**
     * Returns true if there is enough stock for the specified quantity being taken.
     * Throws NotEnoughStockException otherwise.
     *
     * @param int $quantity
     *
     * @return bool
     * @throws NotEnoughStockException
     *
     */
    public function hasEnoughStock(int $quantity = 0)
    {
        /*
         * Using double equals for validation of complete value only, not variable type. For example:
         * '20' (string) equals 20 (int)
         */
        if ($this->available_stock == $quantity || $this->available_stock > $quantity) {
            return true;
        }

        $message = __('hs.inventory::exceptions.NotEnoughStockException', [
            'quantity' => $quantity,
            'available' => $this->available_stock,
        ]);

        throw new NotEnoughStockException($message);
    }

    /**
     * Processes removing quantity from the current stock.
     *
     * @param int $taking
     * @param string $reason
     * @param int $cost
     * @return $this|false
     * @throws \Throwable
     */
    private function processTakeOperation(int $taking, string $reason = '', int $cost = 0)
    {
        $left = $this->available_stock - $taking;

        /*
         * If the updated total and the beginning total are the same, we'll check if
         * duplicate movements are allowed. We'll return the current record if
         * they aren't.
         */
        if ($left == $this->available_stock && !$this->allowDuplicateMovementsEnabled()) {
            return $this;
        }

        $this->available_stock = $left;

        $this->reason = $reason;

        $this->cost = $cost;

        $this->dbStartTransaction();

        try {
            if ($this->save()) {
                $this->dbCommitTransaction();

                $this->fireEvent('inventory.stock.taken', [
                    'stock' => $this,
                ]);

                return $this;
            }
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * Moves a stock to the specified warehouse.
     *
     * @param Warehouse $warehouse
     *
     * @return bool
     */
    public function moveTo(Warehouse $warehouse): bool
    {
        return $this->processMoveOperation($warehouse);
    }

    /**
     * Processes the stock moving from it's current
     *  location, to the specified location.
     *
     * @param Warehouse $warehouse
     * @return bool
     * @throws \Throwable
     */
    private function processMoveOperation(Warehouse $warehouse): bool
    {
        $this->warehouse_id = $warehouse->getKey();

        $this->dbStartTransaction();

        try {
            if ($this->save()) {
                $this->dbCommitTransaction();

                $this->fireEvent('inventory.stock.moved', [
                    'stock' => $this,
                ]);

                return true;
            }
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
            return false;
        }

        return false;
    }

    /**
     * Rolls back the last movement, or the movement specified. If recursive is set to true,
     * it will rollback all movements leading up to the movement specified.
     *
     * @param mixed $movement
     * @param bool $recursive
     *
     * @return $this|bool
     */
    public function rollback($movement = null, $recursive = false)
    {
        if ($movement) {
            return $this->rollbackMovement($movement, $recursive);
        } else {
            $movement = $this->getLastMovement();

            if ($movement) {
                return $this->processRollbackOperation($movement, $recursive);
            }
        }

        return false;
    }

    /**
     * Rolls back a specific movement.
     *
     * @param mixed $movement
     * @param bool $recursive
     *
     * @return $this|bool
     * @throws InvalidMovementException
     *
     */
    public function rollbackMovement($movement, $recursive = false)
    {
        $movement = $this->getMovement($movement);

        return $this->processRollbackOperation($movement, $recursive);
    }

    /**
     * Processes a single rollback operation.
     *
     * @param InventoryStockMovement $movement
     * @param bool $recursive
     *
     * @return $this|bool
     */
    protected function processRollbackOperation(InventoryStockMovement $movement, bool $recursive = false)
    {
        if ($recursive) {
            return $this->processRecursiveRollbackOperation($movement);
        }

        $this->quantity = $movement->before;

        $reason = __('hs.inventory::reasons.rollback', [
            'id' => $movement->getOriginal('id'),
            'date' => $movement->getOriginal('created_at'),
        ]);

        $this->setReason($reason);

        if ($this->rollbackCostEnabled()) {
            $this->setCost($movement->cost);

            $this->reverseCost();
        }

        $this->dbStartTransaction();

        try {
            if ($this->save()) {
                $this->dbCommitTransaction();

                $this->fireEvent('inventory.stock.rollback', [
                    'stock' => $this,
                ]);

                return $this;
            }
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * Processes a recursive rollback operation.
     *
     * @param mixed $movement
     *
     * @return array
     */
    private function processRecursiveRollbackOperation(Model $movement)
    {
        /*
         * Retrieve movements that were created after
         * the specified movement, and order them descending
         */
        $movements = $this
            ->movements()
            ->where('created_at', '>=', $movement->getOriginal('created_at'))
            ->orderBy('created_at', 'DESC')
            ->get();

        $rollbacks = [];

        if ($movements->count() > 0) {
            foreach ($movements as $movement) {
                $rollbacks = $this->processRollbackOperation($movement);
            }
        }

        return $rollbacks;
    }

    /**
     * Returns true/false from the configuration file determining
     * whether or not to rollback costs when a rollback occurs on
     * a stock.
     *
     * @return bool
     */
    private function rollbackCostEnabled()
    {
        return config('hs.inventory.rollback_cost');
    }

    /**
     * Reverses the cost of a movement.
     */
    private function reverseCost()
    {
        if ($this->isPositive($this->cost)) {
            $this->setCost(-abs($this->cost));
        } else {
            $this->setCost(abs($this->cost));
        }
    }

    /**
     * Creates and returns a new un-saved stock transaction
     * instance with the current stock ID attached.
     *
     * @param string $name
     *
     * @return Model
     */
    public function newTransaction($name = '')
    {
        $transaction = $this->transactions()->getRelated();

        /*
         * Set the transaction attributes so they don't
         * need to be set manually
         */
        $transaction->stock_id = $this->getKey();
        $transaction->name = $name;

        return $transaction;
    }
}
