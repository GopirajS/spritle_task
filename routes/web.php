<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
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


    $getAllPost = Post::all();
    return view('welcome',['getAllPost'=>$getAllPost]);
})->name('welcome');

Route::middleware(['auth'])->group(function(){

    Route::get('form',[PostController::class,'createPost'])->name('create.post');
    Route::post('save-post',[PostController::class,'savePost'])->name('save.post');
    Route::post('update-post',[PostController::class,'updatePost'])->name('update.post');

    
    Route::post('/post/{post}/comment', [PostController::class, 'storeComment'])->name('post.comment');
    Route::post('/post/{post}/like', [PostController::class, 'likePost'])->name('post.like');


});


require __DIR__.'/auth.php';
