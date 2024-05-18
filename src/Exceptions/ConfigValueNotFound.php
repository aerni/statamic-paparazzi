<?php

namespace Aerni\Paparazzi\Exceptions;

use Exception;

class ConfigValueNotFound extends Exception
{
    public function __construct(string $key)
    {
        parent::__construct("The [{$key}] does not exist in the config.");
    }
}
