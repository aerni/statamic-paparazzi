<?php

use Aerni\Paparazzi\Http\Controllers\Cp\LivePreviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('paparazzi')->name('paparazzi.')->group(function () {
    Route::get('{model}/{layout}/{template}', LivePreviewController::class)->name('live-preview');
});
