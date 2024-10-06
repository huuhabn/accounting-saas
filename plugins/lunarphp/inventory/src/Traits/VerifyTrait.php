<?php

namespace HS\Inventory\Traits;

use HS\Inventory\Exceptions\InvalidQuantityException;

/**
 * Trait VerifyTrait.
 */
trait VerifyTrait
{
    /**
     * Returns true if the specified quantity is valid, throws
     *  InvalidQuantityException otherwise.
     *
     * @param int $quantity
     * @return bool
     * @throws InvalidQuantityException
     */
    public function isValidQuantity(int $quantity): bool
    {
        if ($this->isPositive($quantity)) {
            return true;
        }

        $message = __('hs.inventory::exceptions.InvalidQuantityException', [
            'quantity' => $quantity,
        ]);

        throw new InvalidQuantityException($message);
    }

    /**
     * Returns true or false if the number inserted is positive.
     *
     * @param float|int|string $number
     *
     * @return bool
     */
    private function isPositive(float|int|string $number): bool
    {
        if ($this->isNumeric($number)) {
            return $number >= 0;
        }

        return false;
    }

    /**
     * Returns true/false if the number specified is numeric.
     *
     * @param int $number
     *
     * @return bool
     */
    private function isNumeric(int $number): bool
    {
        return is_numeric($number);
    }

    /**
     * Returns true/false if the specified model is a subclass
     * of the Eloquent Model.
     *
     * @param mixed $model
     *
     * @return bool
     */
    private function isModel(mixed $model): bool
    {
        return is_subclass_of($model, 'Illuminate\Database\Eloquent\Model');
    }
}
