<?php

namespace Aerni\Paparazzi\Stores;

use Illuminate\Support\Collection;

abstract class Store
{
    abstract public function items(): Collection;

    public function item(string $id): mixed
    {
        return $this->items()->get($id);
    }
}
