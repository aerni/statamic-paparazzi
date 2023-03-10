<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

class Model extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\ModelRepository::class;
    }
}
