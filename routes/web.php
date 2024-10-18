<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function(){

    Route::get('form',[PostController::class,'createPost'])->name('create.post');
    Route::post('save-post',[PostController::class,'savePost'])->name('save.post');
    Route::post('update-post',[PostController::class,'updatePost'])->name('update.post');

});


require __DIR__.'/auth.php';
