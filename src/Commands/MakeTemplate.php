<?php

namespace Aerni\Paparazzi\Commands;

use Aerni\Paparazzi\Facades\Model;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;

class MakeTemplate extends Command
{
    use RunsInPlease;

    protected $signature = 'paparazzi:template {model?} {name?}';
    protected $description = 'Create a new Paparazzi model template';

    public function handle(): void
    {
        $this->publishTemplate();
    }

    protected function publishTemplate(): void
    {
        $model = $this->argument('model') ?? $this->choice('Which model do you want to create a template for?', Model::all()->map->handle()->all());
        $template = $this->argument('name') ?? $this->ask('What do you want to name the template?', config('paparazzi.defaults.template', 'default'));

        $source = __DIR__.'/../../resources/stubs/template.antlers.html';
        $directory = config('paparazzi.views')."/{$model}";
        $file = "$directory/{$template}.antlers.html";

        if (! File::exists($file) || $this->confirm("A template with the name <comment>$template</comment> already exists. Do you want to overwrite it?")) {
            File::ensureDirectoryExists($directory);
            File::copy($source, $file);
            $this->line("<info>[✓]</info> The template was successfully created: <comment>{$this->getRelativePath($file)}</comment>");
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
