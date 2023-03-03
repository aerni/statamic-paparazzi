<?php

use Aerni\ImageGenerator\Http\Controllers\Web\ImageController;
use Illuminate\Support\Facades\Route;

Route::name('image-generator.')->group(function () {
    Route::get('/image/{theme}/{type}/{id}', [ImageController::class, 'show'])->name('images.show');
});
