<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

class Template extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Repositories\TemplateRepository::class;
    }
}
