<?php

namespace Manhamprod\PwaAssetsGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Manhamprod\PwaAssetsGenerator\PwaAssetsGenerator
 */
class PwaAssetsGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pwaassetsgenerator';
    }
}
