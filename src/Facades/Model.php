<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Aerni\Paparazzi\Model make()
 * @method static \Illuminate\Support\Collection all(array|null $models = null)
 * @method static \Aerni\Paparazzi\Model|null find(string $id)
 * @method static \Illuminate\Support\Collection findByHandle(string $handle)
 *
 * @see \Aerni\Paparazzi\Repositories\ModelRepository
 */
class Model extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Repositories\ModelRepository::class;
    }
}
