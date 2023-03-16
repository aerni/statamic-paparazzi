<?php

namespace Aerni\Paparazzi\Commands;

use Aerni\Paparazzi\Facades\Model;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;

class MakeTheme extends Command
{
    use RunsInPlease;

    protected $signature = 'paparazzi:theme {model?} {name?}';
    protected $description = 'Create a new Paparazzi model theme';

    public function handle(): void
    {
        $this->publishTheme();
    }

    protected function publishTheme(): void
    {
        $model = $this->argument('model') ?? $this->choice('For which model do you want to create a theme', Model::all()->map->id()->all());
        $theme = $this->argument('name') ?? $this->ask('What do you want to call the theme?', 'default');

        $source = __DIR__.'/../../resources/stubs/template.antlers.html';
        $directory = config('paparazzi.views') . "/{$model}";
        $file = "$directory/{$theme}.antlers.html";

        if (! File::exists($file) || $this->confirm("A theme with the name <comment>$theme</comment> already exists. Do you want to overwrite it?")) {
            File::ensureDirectoryExists($directory);
            File::copy($source, $file);
            $this->line("<info>[✓]</info> The theme was successfully created: <comment>{$this->getRelativePath($file)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
