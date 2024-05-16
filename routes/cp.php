<?php

use Aerni\Paparazzi\Http\Controllers\Cp\LivePreviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('paparazzi')->name('paparazzi.')->group(function () {
    Route::get('{modelToken}', LivePreviewController::class)->name('live-preview');
});
