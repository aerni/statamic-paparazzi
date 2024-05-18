<?php

namespace Aerni\Paparazzi\Concerns;

use Aerni\Paparazzi\Exceptions\ConfigValueNotFound;
use Illuminate\Support\Facades\Validator;

trait HandlesConfig
{
    protected array $config = [];

    public function get(string $key): mixed
    {
        return $this->config[$key] ?? throw new ConfigValueNotFound($key);
    }

    public function set(string $key, mixed $value): self
    {
        $this->validateOnly([$key => $value]);

        $this->config[$key] = $value;

        return $this;
    }

    public function config(?array $config = null): array|self
    {
        if (is_null($config)) {
            return $this->config;
        }

        foreach ($config as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    protected function defaultConfig(): array
    {
        return [
            'layout' => config('paparazzi.defaults.layout', 'default'),
            'template' => config('paparazzi.defaults.template', 'default'),
            'extension' => config('paparazzi.defaults.extension', 'png'),
            'quality' => config('paparazzi.defaults.quality', 100),
            'container' => config('paparazzi.defaults.container', 'assets'),
            'directory' => config('paparazzi.defaults.directory', '{type}/{parent}/{site}/{slug}'),
            'reference' => config('paparazzi.defaults.reference', '{model}-{layout}-{template}-{parent}-{site}-{slug}'),
            'replace' => config('paparazzi.defaults.replace', true),
        ];
    }

    protected function rules(): array
    {
        return [
            'handle' => 'required|string',
            'width' => 'required|integer|numeric',
            'height' => 'required|integer|numeric',
            'layout' => 'required|string',
            'template' => 'required|string',
            'extension' => 'required|string|in:png,jpeg,pdf',
            'quality' => 'required|integer|numeric',
            'container' => 'required|string',
            'directory' => 'required|string',
            'reference' => 'required|string',
            'replace' => 'required|bool',
        ];
    }

    protected function validate(array $config): void
    {
        Validator::make($config, $this->rules())->validate();
    }

    protected function validateOnly(array $config): void
    {
        Validator::make($config, array_intersect_key($this->rules(), $config))->validate();
    }
}
