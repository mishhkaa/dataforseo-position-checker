<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataForSeoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DataForSeoController::class, 'index'])->name('search.index');
Route::post('/search', [DataForSeoController::class, 'search'])->name('search.perform');
