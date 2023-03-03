<?php

namespace Aerni\ImageGenerator\Jobs;

use Aerni\ImageGenerator\Facades\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Contracts\Entries\Entry;

class GenerateImagesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(protected Entry $entry)
    {
        $this->queue = config('image-generator.queue', 'default');
    }

    public function handle(): void
    {
        Image::all($this->entry)->each->generate();
    }
}
