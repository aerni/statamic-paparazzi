<?php

namespace Aerni\Paparazzi\Exceptions;

use Exception;

class ModelNotFound extends Exception
{
    public function __construct(string $model)
    {
        parent::__construct("Model [{$model}] does not exist.");
    }
}
