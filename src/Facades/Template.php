<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection all()
 * @method static \Aerni\Paparazzi\Template|null find(string $id)
 * @method static \Aerni\Paparazzi\Template findOrFail(string $id)
 * @method static \Illuminate\Support\Collection allOfModel(string $handle)
 *
 * @see \Aerni\Paparazzi\Repositories\TemplateRepository
 */
class Template extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Repositories\TemplateRepository::class;
    }
}
