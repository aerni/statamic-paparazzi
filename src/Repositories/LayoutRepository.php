<?php

namespace Aerni\Paparazzi\Repositories;

use Aerni\Paparazzi\Exceptions\LayoutNotFound;
use Aerni\Paparazzi\Layout;
use Aerni\Paparazzi\Stores\LayoutsStore;
use Illuminate\Support\Collection;

class LayoutRepository
{
    public function __construct(protected LayoutsStore $store)
    {
        //
    }

    public function all(): Collection
    {
        return $this->store->items();
    }

    public function find(string $id): ?Layout
    {
        return $this->store->item($id);
    }

    public function findOrFail(string $id): Layout
    {
        return $this->find($id) ?? throw new LayoutNotFound($id);
    }
}
