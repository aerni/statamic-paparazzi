<?php

use Illuminate\Support\Facades\File;

it('publishes config after install', function () {
    Artisan::call('statamic:install');

    expect(file_exists(config_path('paparazzi.php')))->toBeTrue();

    File::delete(config_path('paparazzi.php'));
});
