<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Concerns\ExistsAsFile;

class Layout
{
    use ExistsAsFile;

    public function isDefault(): bool
    {
        return $this->handle() === config('paparazzi.defaults.layout');
    }
}
