<?php

use Aerni\Paparazzi\Facades\Paparazzi;
use Aerni\Paparazzi\Http\Controllers\Web\PaparazziController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::get(Paparazzi::route(), [PaparazziController::class, 'show'])->name('paparazzi');
}
