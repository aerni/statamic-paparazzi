<?php

namespace Aerni\Paparazzi;

use SplFileInfo;
use Illuminate\Support\Collection;
use Aerni\Paparazzi\Exceptions\LayoutNotFound;
use Aerni\Paparazzi\Stores\LayoutsStore;

class LayoutRepository
{
    public function __construct(protected LayoutsStore $store)
    {
        //
    }

    public function all(): Collection
    {
        return $this->store
            ->map(fn ($file) => $this->resolve($file))
            ->values();
    }

    public function find(string $id): ?Layout
    {
        return $this->store
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
