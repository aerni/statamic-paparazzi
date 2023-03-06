<?php

namespace Aerni\Paparazzi;

use SplFileInfo;
use Statamic\Support\Str;

class Template
{
    public function __construct(protected SplFileInfo $file)
    {
        //
    }

    public function id(): string
    {
        return $this->file->getBasename('.antlers.html');
    }

    public function name(): string
    {
        return Str::slugToTitle($this->id());
    }

    public function view(): string
    {
        return Str::after($this->file->getPathname(), 'views/');
    }
}
