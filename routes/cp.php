<?php

use Aerni\Paparazzi\Facades\Paparazzi;
use Aerni\Paparazzi\Http\Controllers\Web\LivePreviewController;
use Illuminate\Support\Facades\Route;

Route::get(Paparazzi::route('{model}/{layout}/{template}'), LivePreviewController::class)->name('paparazzi.live-preview');
