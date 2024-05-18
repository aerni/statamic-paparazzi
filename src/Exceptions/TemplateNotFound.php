<?php

namespace Aerni\Paparazzi\Exceptions;

use Exception;

class TemplateNotFound extends Exception
{
    public function __construct(string $template)
    {
        parent::__construct("Template [{$template}] does not exist.");
    }
}
