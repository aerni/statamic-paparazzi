<?php

namespace Aerni\ImageGenerator\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Facades\Folder;

class Theme extends Model
{
    protected static function getRows(): array
    {
        $themes = Folder::disk('resources')
            ->getFolders('views/social_images')
            ->map(function ($path) {
                $handle = Str::of($path)->basename()->snake()->jsonSerialize();
                $title = Str::of($handle)->replace('_', ' ')->title()->jsonSerialize();

                $templates = Folder::disk('resources')->getFiles($path)
                    ->mapWithKeys(function ($template) {
                        $key = Str::of($template)->basename('.antlers.html')->jsonSerialize();
                        $view = Str::of($template)->remove('views/')->remove('.antlers.html')->jsonSerialize();

                        return [$key => $view];
                    });

                if ($missingTemplate = collect(SocialImage::$types)->flip()->diffKeys($templates)->flip()->first()) {
                    throw new \Exception("Please add the \"{$missingTemplate}.antlers.html\" template to your \"{$handle}\" social images theme.");
                }

                return [
                    'handle' => $handle,
                    'title' => $title,
                    'templates' => $templates,
                ];
            })
            ->sortBy('title')
            ->values();

        if ($themes->isEmpty()) {
            throw new \Exception('You need to create at least one theme for your social images.');
        }

        return $themes->toArray();
    }

    protected static function all(): Collection
    {
        return static::$rows;
    }

    protected static function templatesOfType(string $id): Collection
    {
        return static::$rows->mapWithKeys(function ($theme) use ($id) {
            return [$theme['handle'] => collect($theme['templates'])->first(fn ($view, $key) => $key === $id)];
        })->filter();
    }

    protected static function fieldtypeOptions(): array
    {
        return static::$rows->flatMap(fn ($theme) => [$theme['handle'] => $theme['title']])->toArray();
    }

    protected static function fieldtypeDefault(): string
    {
        $theme = static::$rows->firstWhere('handle', 'default') ?? static::$rows->first();

        return $theme['handle'];
    }
}
