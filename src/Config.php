<?php

namespace Aerni\Paparazzi;

use Illuminate\Support\Facades\Validator;

class Config
{
    protected int $width;
    protected int $height;
    protected string $extension;
    protected int $quality;
    protected string $container;
    protected string $directory;
    protected bool $replace;
    protected string $layout;
    protected string $template;

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
            'extension' => config('paparazzi.defaults.extension', 'png'),
            'quality' => config('paparazzi.defaults.quality', 100),
            'container' => config('paparazzi.defaults.container', 'assets'),
            'replace' => config('paparazzi.defaults.replace', true),
            'layout' => config('paparazzi.defaults.layout', 'default'),
            'template' => config('paparazzi.defaults.template', 'default'),
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
            'extension' => 'string|in:png,jpeg,pdf',
            'quality' => 'integer',
            'container' => 'string',
            'directory' => 'string',
            'replace' => 'bool',
            'layout' => 'string',
            'template' => 'string',
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
