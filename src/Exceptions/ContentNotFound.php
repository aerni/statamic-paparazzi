<?php

namespace Aerni\Paparazzi\Exceptions;

use Exception;

class ContentNotFound extends Exception
{
    public function __construct(string $content)
    {
        parent::__construct("Content with ID [{$content}] does not exist.");
    }
}
