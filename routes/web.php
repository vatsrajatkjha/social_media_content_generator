<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StableDiffusionController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ImageGenerationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('contents.index');
});

Route::resource('contents', ContentController::class)
    ->except(['edit', 'update', 'destroy']);

Route::get('/contents/{content}/edit', [ContentController::class, 'edit'])->name('contents.edit');
Route::put('/contents/{content}', [ContentController::class, 'update'])->name('contents.update');
Route::post('/contents/{id}/regenerate', [ContentController::class, 'regenerate'])->name('contents.regenerate');
// Social Media Content Generator routes
Route::get('/generate', [StableDiffusionController::class, 'index'])->name('stable-diffusion.index');
Route::post('/generate', [StableDiffusionController::class, 'generate'])->name('stable-diffusion.generate');
Route::post('/generate/{id}/regenerate', [StableDiffusionController::class, 'regenerate'])->name('stable-diffusion.regenerate');

Route::get('/generated-images', [StableDiffusionController::class, 'list'])->name('generated-images.index');

Route::get('/generate-image', [ImageGenerationController::class, 'showForm'])->name('image.form');
Route::post('/generate-image', [ImageGenerationController::class, 'generate'])->name('image.generate');
