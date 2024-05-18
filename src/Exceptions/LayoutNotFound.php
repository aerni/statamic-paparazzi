<?php

namespace Aerni\Paparazzi\Exceptions;

use Exception;

class LayoutNotFound extends Exception
{
    public function __construct(string $layout)
    {
        parent::__construct("Layout [{$layout}] does not exist.");
    }
}
