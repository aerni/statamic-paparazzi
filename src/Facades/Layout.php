<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection all()
 * @method static \Aerni\Paparazzi\Layout|null find(string $id)
 * @method static \Aerni\Paparazzi\Layout findOrFail(string $id)
 *
 * @see \Aerni\Paparazzi\Repositories\LayoutRepository
 */
class Layout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Repositories\LayoutRepository::class;
    }
}
