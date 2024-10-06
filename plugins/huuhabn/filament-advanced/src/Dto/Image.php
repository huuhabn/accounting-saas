<?php

namespace HanaSales\FilamentAdvanced\Dto;

readonly class Image
{
    /**
     * Initialise the postcode lookup class.
     *
     * @param string image
     * @param string attribution
     */
    public function __construct(
        public string  $image,
        public ?string $attribution = null
    ) {
        //
    }
}
