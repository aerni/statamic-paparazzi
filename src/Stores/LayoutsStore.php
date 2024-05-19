<?php

namespace Aerni\Paparazzi\Stores;

use SplFileInfo;
use Spatie\Blink\Blink;
use Aerni\Paparazzi\Layout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class LayoutsStore extends Store
{
    public function items(): Collection
    {
        return Blink::global()->once('paparazzi-layouts', function () {
            return collect(File::allFiles(config('paparazzi.views')))
                ->filter(fn (SplFileInfo $file) => empty($file->getRelativePath()))
                ->mapInto(Layout::class)
                ->mapWithKeys(fn (Layout $layout) => [$layout->id() => $layout]);
        });
    }
}
