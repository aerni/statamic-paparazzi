<?php

use Aerni\Paparazzi\Http\Controllers\Web\EntryModelController;
use Aerni\Paparazzi\Http\Controllers\Web\ModelController;
use Aerni\Paparazzi\Http\Controllers\Web\TermModelController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::prefix(config('paparazzi.preview_url', '/paparazzi'))->name('paparazzi.')->group(function () {
        Route::get('{model}/{layout}/{template}/collections/{entry}/{site?}', EntryModelController::class)->name('entry');
        Route::get('{model}/{layout}/{template}/taxonomies/{taxonomy}/{term}/{site?}', TermModelController::class)->name('term');
        Route::get('{model}/{layout}/{template}', ModelController::class)->name('model');
    });
}
