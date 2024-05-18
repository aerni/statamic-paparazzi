<?php

namespace Aerni\Paparazzi\Repositories;

use SplFileInfo;
use Aerni\Paparazzi\Template;
use Illuminate\Support\Collection;
use Aerni\Paparazzi\Stores\TemplatesStore;
use Aerni\Paparazzi\Exceptions\TemplateNotFound;

class TemplateRepository
{
    public function __construct(protected TemplatesStore $store)
    {
        //
    }

    public function all(): Collection
    {
        return $this->store
            ->map(fn ($file) => $this->resolve($file))
            ->values();
    }

    public function find(string $id): ?Template
    {
        return $this->store
            ->map(fn ($file) => $this->resolve($file))
            ->firstWhere(fn ($template) => $template->id() === $id);
    }

    public function findOrFail(string $id): Template
    {
        return $this->find($id) ?? throw new TemplateNotFound($id);
    }

    public function allOfModel(string $handle): Collection
    {
        return $this->store
            ->map(fn ($file) => $this->resolve($file))
            ->filter(fn ($template) => $template->model() === $handle)
            ->values();
    }

    protected function resolve(SplFileInfo $file): Template
    {
        return new Template($file);
    }
}
