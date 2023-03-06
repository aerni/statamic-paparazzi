<?php

namespace Aerni\Paparazzi\Jobs;

use Illuminate\Bus\Queueable;
use Aerni\Paparazzi\Generator;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateAssetJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(protected Generator $generator)
    {
        $this->queue = config('paparazzi.queue', 'default');
    }

    public function handle()
    {
        $this->generator->generate();
    }
}
