<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Concerns\ExistsAsFile;

class Template
{
    use ExistsAsFile;

    public function id(): string
    {
        return "{$this->model()}::{$this->handle()}";
    }

    public function model(): string
    {
        return $this->file->getRelativePath();
    }
}
