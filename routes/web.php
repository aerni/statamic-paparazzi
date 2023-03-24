<?php

use Aerni\Paparazzi\Facades\Paparazzi;
use Aerni\Paparazzi\Http\Controllers\Web\EntryModelController;
use Aerni\Paparazzi\Http\Controllers\Web\ModelController;
use Aerni\Paparazzi\Http\Controllers\Web\TermModelController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::get(Paparazzi::route('{model}/{layout}/{template}/collections/{entry}/{site?}'), EntryModelController::class);
    Route::get(Paparazzi::route('{model}/{layout}/{template}/taxonomies/{taxonomy}/{term}/{site?}'), TermModelController::class);
    Route::get(Paparazzi::route('{model}/{layout}/{template}'), ModelController::class);
}
