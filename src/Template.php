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
        return "{$this->model()}::{$this->file->getBasename('.antlers.html')}";
    }

    public function name(): string
    {
        return Str::slugToTitle($this->file->getBasename('.antlers.html'));
    }

    public function model(): string
    {
        return $this->file->getRelativePath();
    }

    public function view(): string
    {
        $viewPath = Str::after($this->file->getPathname(), 'views/');

        return Str::remove('.antlers.html', $viewPath);
    }
}
