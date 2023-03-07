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
    protected string $layout;
    protected string $template;

    public function __construct(array $config)
    {
        $this->init($config);
    }

    protected function init(array $config): void
    {
        $config = array_merge([
            'extension' => config('paparazzi.defaults.extension', 'png'),
            'quality' => config('paparazzi.defaults.quality', 100),
            'container' => config('paparazzi.defaults.container', 'assets'),
            'directory' => config('paparazzi.defaults.directory', '/'),
            'layout' => config('paparazzi.defaults.layout', 'layout'),
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
            return $this->$name;
        }

        $this->validate([$name => $arguments[0]], true);

        $this->$name = $arguments[0];
    }
}
