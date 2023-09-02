<?php

namespace Aerni\Paparazzi\Commands;

use Aerni\Paparazzi\Facades\Model;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use Statamic\Console\RunsInPlease;

class MakeTemplate extends Command
{
    use RunsInPlease;

    protected $signature = 'paparazzi:template';

    protected $description = 'Create a new Paparazzi model template';

    public function handle(): void
    {
        $this->publishTemplate();
    }

    protected function publishTemplate(): void
    {
        $model = select(
            label: 'Which model do you want to create a template for?',
            options: Model::all()->map->handle()->all(),
        );

        $template = text(
            label: 'What is the name of this template?',
            required: 'The name is required.',
            default: config('paparazzi.defaults.template', 'default')
        );

        $source = __DIR__.'/../../resources/stubs/template.antlers.html';
        $directory = config('paparazzi.views')."/{$model}";
        $file = "$directory/{$template}.antlers.html";

        if (! File::exists($file) || confirm('A template with this name already exists. Do you want to overwrite it?')) {
            File::ensureDirectoryExists($directory);
            File::copy($source, $file);
            info("The template was successfully created: <comment>{$this->getRelativePath($file)}</comment>");
        } else {
            error('No template was created.');
        }
    }

    protected function getRelativePath($path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
