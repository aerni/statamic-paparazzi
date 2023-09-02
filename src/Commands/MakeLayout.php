<?php

namespace Aerni\Paparazzi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;
use Statamic\Console\RunsInPlease;

class MakeLayout extends Command
{
    use RunsInPlease;

    protected $signature = 'paparazzi:layout';

    protected $description = 'Create a new Paparazzi layout';

    public function handle(): void
    {
        $this->publishLayout();
    }

    protected function publishLayout(): void
    {
        $layout = text(
            label: 'What is the name of this layout?',
            required: 'The name is required.',
            default: config('paparazzi.defaults.layout', 'default')
        );

        $source = __DIR__.'/../../resources/stubs/layout.antlers.html';
        $directory = config('paparazzi.views');
        $file = "$directory/{$layout}.antlers.html";

        if (! File::exists($file) || confirm('A layout with this name already exists. Do you want to overwrite it?')) {
            File::ensureDirectoryExists($directory);
            File::copy($source, $file);
            info("The layout was successfully created: <comment>{$this->getRelativePath($file)}</comment>");
        } else {
            error('No layout was created.');
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
