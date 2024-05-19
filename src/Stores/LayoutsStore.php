<?php

namespace Aerni\Paparazzi\Stores;

use Aerni\Paparazzi\Layout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class LayoutsStore extends Store
{
    protected string $key = 'paparazzi-layouts';

    protected function makeItems(): Collection
    {
        return collect(File::allFiles(config('paparazzi.views')))
            ->filter(fn (SplFileInfo $file) => empty($file->getRelativePath()))
            ->mapInto(Layout::class)
            ->mapWithKeys(fn (Layout $layout) => [$layout->id() => $layout]);
    }
}
