<?php

namespace Aerni\Paparazzi;

use Illuminate\Support\Facades\Validator;

class Config
{
    protected int $width;
    protected int $height;
    protected string $layout;
    protected string $template;
    protected string $extension;
    protected int $quality;
    protected string $container;
    protected string $directory;
    protected string $reference;
    protected bool $replace;

    public function __construct(array $config)
    {
        $this->init($config);
    }

    public function all(): array
    {
        return get_object_vars($this);
    }

    protected function init(array $config): void
    {
        $config = array_merge([
            'layout' => config('paparazzi.defaults.layout', 'default'),
            'template' => config('paparazzi.defaults.template', 'default'),
            'extension' => config('paparazzi.defaults.extension', 'png'),
            'quality' => config('paparazzi.defaults.quality', 100),
            'container' => config('paparazzi.defaults.container', 'assets'),
            'directory' => config('paparazzi.defaults.directory', '{type}/{parent}/{site}/{slug}'),
            'reference' => config('paparazzi.defaults.reference', '{model}-{layout}-{template}-{parent}-{site}-{slug}'),
            'replace' => config('paparazzi.defaults.replace', true),
        ], $config);

        $this->validate($config);

        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    protected function validate(array $config, bool $only = false): void
    {
        $validatables = [
            'width' => 'required|integer',
            'height' => 'required|integer',
            'layout' => 'required|string',
            'template' => 'required|string',
            'extension' => 'required|string|in:png,jpeg,pdf',
            'quality' => 'required|integer',
            'container' => 'required|string',
            'directory' => 'required|string',
            'reference' => 'required|string',
            'replace' => 'required|bool',
        ];

        /**
         * Only validate the values that exist in the config.
         * This is needed to validate single values in the __call method.
         */
        if ($only) {
            $validatables = array_intersect_key($validatables, $config);
        }

        Validator::make($config, $validatables)->validate();
    }

    public function __call(string $name, array $arguments)
    {
        if (! property_exists($this, $name)) {
            return;
        }

        if (empty($arguments)) {
            return isset($this->$name) ? $this->$name : null;
        }

        $this->validate([$name => $arguments[0]], true);

        $this->$name = $arguments[0];
    }
}
