<?php

namespace Aerni\Paparazzi\Stores;

use Illuminate\Support\Collection;
use Spatie\Blink\Blink;

abstract class Store
{
    protected string $key;

    abstract protected function makeItems(): Collection;

    public function items(): Collection
    {
        return Blink::global()->once($this->key, fn () => $this->makeItems());
    }

    public function item(string $id): mixed
    {
        return $this->items()->get($id);
    }
}
