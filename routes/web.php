<?php

use Aerni\Paparazzi\Facades\Paparazzi;
use Aerni\Paparazzi\Http\Controllers\Web\ImageController;
use Illuminate\Support\Facades\Route;

Route::get(Paparazzi::route(), [ImageController::class, 'show'])->name('images.show');
