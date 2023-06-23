<?php

namespace Aerni\Paparazzi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;

class MakeLayout extends Command
{
    use RunsInPlease;

    protected $signature = 'paparazzi:layout {name?}';

    protected $description = 'Create a new Paparazzi layout';

    public function handle(): void
    {
        $this->publishLayout();
    }

    protected function publishLayout(): void
    {
        $layout = $this->argument('name') ?? $this->ask('What do you want to name the layout?', config('paparazzi.defaults.layout', 'default'));

        $source = __DIR__.'/../../resources/stubs/layout.antlers.html';
        $directory = config('paparazzi.views');
        $file = "$directory/{$layout}.antlers.html";

        if (! File::exists($file) || $this->confirm("A layout with the name <comment>$layout</comment> already exists. Do you want to overwrite it?")) {
            File::ensureDirectoryExists($directory);
            File::copy($source, $file);
            $this->line("<info>[✓]</info> The layout was successfully created: <comment>{$this->getRelativePath($file)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
