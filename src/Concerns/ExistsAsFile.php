<?php

namespace Aerni\Paparazzi\Concerns;

use SplFileInfo;
use Statamic\Support\Str;

trait ExistsAsFile
{
    public function __construct(protected SplFileInfo $file)
    {
        //
    }

    public function id(): string
    {
        return $this->handle();
    }

    public function handle(): string
    {
        return $this->file->getBasename('.antlers.html');
    }

    public function name(): string
    {
        return Str::slugToTitle($this->handle());
    }

    public function view(): string
    {
        $viewPath = Str::after($this->file->getPathname(), 'views/');

        return Str::remove('.antlers.html', $viewPath);
    }

    public function __toString(): string
    {
        return $this->handle();
    }
}
