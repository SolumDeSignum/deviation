<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Facades;

use Illuminate\Support\Facades\Facade;

class Deviation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'deviation';
    }
}
