<?php

namespace HS\Inventory\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Spatie\LaravelBlink\BlinkFacade as Blink;

trait HasDefaultRecord
{
    /**
     * Return the default scope.
     *
     * @param Builder  $query
     * @return void
     */
    public function scopeDefault($query, $default = true)
    {
        $query->whereDefault($default);
    }

    /**
     * Get the default record.
     *
     * @return self
     */
    public static function getDefault()
    {
        $key = 'hs_default_'.Str::snake(self::class);

        return Blink::once($key, function () {
            return self::query()->default(true)->first();
        });
    }
}
