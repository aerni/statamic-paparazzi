<?php

namespace Aerni\ImageGenerator\Image;

use Aerni\ImageGenerator\Models\Image;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Contracts\Entries\Entry;

class ImageRepository
{
    public function all(Entry $entry): Collection
    {
        return Image::groups()
            ->map(fn ($group, $type) => $this->{Str::camel($type)}($entry));
    }

    public function findModel(string $type): ?array
    {
        return Image::firstWhere('type', $type);
    }

    public function openGraph(Entry $entry): SocialImage
    {
        return new SocialImage($entry, $this->findModel('open_graph'));
    }

    public function twitter(Entry $entry): SocialImage
    {
        return new SocialImage($entry, $this->findModel("twitter_{$entry->seo_twitter_card}"));
    }

    public function previewTargets(): array
    {
        return [
            [
                'label' => 'Open Graph Image',
                'format' => $this->route(theme: '{seo_social_images_theme}', type: 'open_graph', id: '{id}'),
            ],
            [
                'label' => 'Twitter Image',
                'format' => $this->route(theme: '{seo_social_images_theme}', type: "twitter_{seo_twitter_card}", id: '{id}'),
            ],
        ];
    }

    public function route(string $theme, string $type, string $id): string
    {
        return "/!/image-generator/image/{$theme}/{$type}/{$id}";
    }

    public function sizeString(string $type): string
    {
        $type = $this->findModel($type);

        return "{$type['width']} x {$type['height']} pixels";
    }
}
