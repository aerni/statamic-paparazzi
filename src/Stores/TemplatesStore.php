<?php

namespace Aerni\Paparazzi\Stores;

use SplFileInfo;
use Aerni\Paparazzi\Template;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class TemplatesStore extends Store
{
    public function items(): Collection
    {
        return collect(File::allFiles(config('paparazzi.views')))
            ->filter(fn (SplFileInfo $file) => ! empty($file->getRelativePath()))
            ->mapInto(Template::class)
            ->mapWithKeys(fn (Template $template) => [$template->id() => $template]);
    }
}