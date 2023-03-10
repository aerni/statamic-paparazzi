<?php

namespace Aerni\Paparazzi;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class TemplateRepository
{
    protected Collection $templates;

    public function __construct()
    {
        $this->templates = collect(File::allFiles(config('paparazzi.views')))
            ->filter(fn ($file) => ! empty($file->getRelativePath()))
            ->values();
    }

    public function all(): Collection
    {
        return $this->templates
            ->map(fn ($file) => $this->resolve($file))
            ->values();
    }

    public function find(string $id): ?Template
    {
        return $this->templates
            ->map(fn ($file) => $this->resolve($file))
            ->firstWhere(fn ($template) => $template->id() === $id);
    }

    public function allOfModel(string $id): Collection
    {
        return $this->templates
            ->map(fn ($file) => $this->resolve($file))
            ->filter(fn ($template) => $template->model() === $id)
            ->values();
    }

    protected function resolve(SplFileInfo $file): Template
    {
        return new Template($file);
    }
}
