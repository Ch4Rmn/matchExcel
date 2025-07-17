<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NameMatchController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/welcome', [NameMatchController::class, 'index']);
Route::post('/name-match/compare', [NameMatchController::class, 'compare'])->name('name.match.compare');
Route::get('/name-match/export', [NameMatchController::class, 'export'])->name('name.match.export');
