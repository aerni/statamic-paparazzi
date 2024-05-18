<?php

namespace Aerni\Paparazzi;

use SplFileInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Aerni\Paparazzi\Exceptions\LayoutNotFound;

class LayoutRepository
{
    protected Collection $layouts;

    public function __construct()
    {
        $this->layouts = collect(File::allFiles(config('paparazzi.views')))
            ->filter(fn ($file) => empty($file->getRelativePath()))
            ->values();
    }

    public function all(): Collection
    {
        return $this->layouts
            ->map(fn ($file) => $this->resolve($file))
            ->values();
    }

    public function find(string $id): ?Layout
    {
        return $this->layouts
            ->map(fn ($file) => $this->resolve($file))
            ->firstWhere(fn ($layout) => $layout->id() === $id);
    }

    public function findOrFail(string $id): Layout
    {
        return $this->find($id) ?? throw new LayoutNotFound($id);
    }

    protected function resolve(SplFileInfo $file): Layout
    {
        return new Layout($file);
    }
}
