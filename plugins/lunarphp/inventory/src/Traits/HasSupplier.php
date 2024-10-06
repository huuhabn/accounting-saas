<?php

namespace HS\Inventory\Traits;

use Exception;
use HS\Inventory\Exceptions\InvalidItemException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait SupplierTrait.
 */
trait HasSupplier
{
    use HasDbTransaction;

    use VerifyTrait;

    /**
     * Adds all the specified items to the current supplier.
     */
    public function addItems(array $items = []): bool
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }

        return true;
    }

    /**
     * Adds the specified item to the current supplier.
     *
     * @param mixed $item
     *
     * @return bool
     * @throws InvalidItemException
     *
     */
    public function addItem($item): bool
    {
        $this->getItem($item);

        return $this->processItemAttach($item);
    }

    /**
     * Retrieves the specified item.
     *
     * @param mixed $item
     *
     * @return mixed
     * @throws InvalidItemException
     *
     */
    public function getItem($item)
    {
        if ($this->isNumeric($item)) {
            return $this->getItemById($item);
        } elseif ($this->isModel($item)) {
            return $item;
        } else {
            $message = __('hs.inventory.exceptions.InvalidItemException', [
                'item' => $item,
            ]);

            throw new InvalidItemException($message);
        }
    }

    /**
     * Retrieves an item by the specified ID.
     *
     * @param int|string $id
     *
     * @return mixed
     */
    private function getItemById($id)
    {
        return $this->items()->find($id);
    }

    /**
     * The belongsToMany items relationship.
     *
     */
    abstract public function items(): BelongsToMany;

    /**
     * Processes attaching the specified item to the current supplier.
     *
     * @param mixed $item
     *
     * @return bool
     */
    private function processItemAttach($item)
    {
        $this->dbStartTransaction();

        try {
            $this->items()->attach($item);

            $this->dbCommitTransaction();

            $this->fireEvent('inventory.supplier.attached', [
                'item' => $item,
                'supplier' => $this,
            ]);

            return true;
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * Removes all items from the current supplier.
     *
     * @return bool
     */
    public function removeAllItems(): bool
    {
        $items = $this->items()->get();

        foreach ($items as $item) {
            $this->removeItem($item);
        }

        return true;
    }

    /**
     * Removes the specified item from the current supplier.
     *
     * @param mixed $item
     *
     * @return bool
     * @throws InvalidItemException
     *
     */
    public function removeItem($item)
    {
        $item = $this->getItem($item);

        return $this->processItemDetach($item);
    }

    /**
     * Processes detaching the specified item from the current supplier.
     *
     * @param mixed $item
     *
     * @return bool
     */
    private function processItemDetach($item)
    {
        $this->dbStartTransaction();

        try {
            $this->items()->detach($item);

            $this->dbCommitTransaction();

            $this->fireEvent('inventory.supplier.detached', [
                'item' => $item,
                'supplier' => $this,
            ]);

            return true;
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * Removes all the specified items from the current supplier.
     *
     * @param array $items
     *
     * @return bool
     */
    public function removeItems(array $items = []): bool
    {
        foreach ($items as $item) {
            $this->removeItem($item);
        }

        return true;
    }
}
