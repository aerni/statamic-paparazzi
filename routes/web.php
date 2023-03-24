<?php

use Aerni\Paparazzi\Facades\Paparazzi;
use Aerni\Paparazzi\Http\Controllers\Web\PaparazziController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::get(Paparazzi::route('{model}/{layout}/{template}/{contentType}/{contentId}/{contentSite?}'), PaparazziController::class);
    Route::get(Paparazzi::route('{model}/{layout}/{template}'), PaparazziController::class);
}
