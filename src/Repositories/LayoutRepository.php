<?php

namespace Aerni\Paparazzi\Repositories;

use Aerni\Paparazzi\Layout;
use Illuminate\Support\Collection;
use Aerni\Paparazzi\Stores\LayoutsStore;
use Aerni\Paparazzi\Exceptions\LayoutNotFound;

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
