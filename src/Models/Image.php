<?php

namespace Aerni\ImageGenerator\Models;

use Illuminate\Support\Collection;

class Image extends Model
{
    protected static function getRows(): array
    {
        return config('image-generator.models', []);
    }

    protected static function all(): Collection
    {
        return static::$rows;
    }
}
