<?php

namespace Aerni\Paparazzi;

use SplFileInfo;
use Statamic\Support\Str;

class Layout
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
        $viewPath = Str::after($this->file->getPathname(), 'views/');

        return Str::remove('.antlers.html', $viewPath);
    }
}
