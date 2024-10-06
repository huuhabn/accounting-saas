<?php

namespace HanaSales\FilamentAdvanced\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HanaSales\FilamentAdvanced\FilamentAdvanced
 */
class FilamentAdvanced extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \HanaSales\FilamentAdvanced\FilamentAdvanced::class;
    }
}
