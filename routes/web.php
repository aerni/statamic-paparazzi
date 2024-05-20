<?php

use Aerni\Paparazzi\Http\Controllers\Web\ModelController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::prefix(config('paparazzi.preview_url', '/paparazzi'))->name('paparazzi.')->group(function () {
        Route::get('{model}', ModelController::class)->name('model');
    });
}
