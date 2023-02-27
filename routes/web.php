<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\MydayController;
use App\Http\Controllers\ProfileController;
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
    return redirect(route('mydays.index'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::resource('mydays', MydayController::class)
        ->only(['index','store', 'edit', 'update', 'destroy'])
        ->middleware(['auth','verified']);

// comment route 
Route::resource('comments', CommentsController::class)
        ->only(['store'])
        ->middleware(['auth','verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mydays/ajaxMyday',  [MydayController::class, 'ajaxMyday'])->name('mydays.ajaxMyday');
    Route::get('/mydays/visitOther/{id}',  [MydayController::class, 'visitOther'])->name('mydays.visitOther');
});

require __DIR__.'/auth.php';
