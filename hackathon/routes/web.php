<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\TableController;

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
});

Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
Route::post('/upload-dataset', [UploadController::class, 'receive_dataset'])->name('upload.upload');

Route::get('/select-table',  [TableController::class, 'index'])->name('table.index');
Route::get('/display-table', [TableController::class, 'display'])->name('table.display');