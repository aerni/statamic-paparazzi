<?php

namespace Aerni\Paparazzi\Repositories;

use Aerni\Paparazzi\Exceptions\TemplateNotFound;
use Aerni\Paparazzi\Stores\TemplatesStore;
use Aerni\Paparazzi\Template;
use Illuminate\Support\Collection;

class TemplateRepository
{
    public function __construct(protected TemplatesStore $store)
    {
        //
    }

    public function all(): Collection
    {
        return $this->store->items();
    }

    public function find(string $id): ?Template
    {
        return $this->store->item($id);
    }

    public function findOrFail(string $id): Template
    {
        return $this->find($id) ?? throw new TemplateNotFound($id);
    }

    public function allOfModel(string $handle): Collection
    {
        return $this->store->items()
            ->filter(fn (Template $template) => $template->model() === $handle);
    }
}
