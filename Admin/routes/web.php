<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlogController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// home route
Route::get('home',[HomeController::class,'show']);
Route::post('store',[HomeController::class,'store']);
Route::get('getData/{id}',[HomeController::class,'getData']);
Route::post('update',[HomeController::class,'update']);
Route::get('delete/{id}',[HomeController::class,'delete']);


// about route
Route::get('about',[AboutController::class,'show']);

Route::post('about/store',[AboutController::class,'store']);
Route::get('about/getData/{id}',[AboutController::class,'getData']);
Route::post('about/update',[AboutController::class,'update']);
Route::get('about/delete/{id}',[AboutController::class,'delete']);
// service route
Route::get('service',[ServiceController::class,'show']);
Route::post('service/store',[ServiceController::class,'store']);
Route::get('service/getData/{id}',[ServiceController::class,'getData']);
Route::post('service/update',[ServiceController::class,'update']);
Route::get('service/delete/{id}',[ServiceController::class,'delete']);
// // project route
Route::get('project',[ProjectController::class,'show']);
Route::post('project/store',[ProjectController::class,'store']);
Route::get('project/getData/{id}',[ProjectController::class,'getData']);
Route::post('project/update',[ProjectController::class,'update']);
Route::get('project/delete/{id}',[ProjectController::class,'delete']);
// // blog route
Route::get('blog',[BlogController::class,'show']);
Route::post('blog/store',[BlogController::class,'store']);
Route::get('blog/getData/{id}',[BlogController::class,'getData']);
Route::post('blog/update',[BlogController::class,'update']);
Route::get('blog/delete/{id}',[BlogController::class,'delete']);
require __DIR__.'/auth.php';
