<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    //route for manage-review
    Route::get('/recipes/{recipe}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/recipes/{recipe}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/recipes/{recipe}/reviews/draft', [ReviewController::class, 'saveDraft'])->name('reviews.draft');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/recipes/{recipe}/reviews/load-more', [ReviewController::class, 'loadMore'])->name('reviews.load-more');

});

require __DIR__.'/auth.php';
