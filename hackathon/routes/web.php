<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ManipulateDataController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\LaunchController;

use Illuminate\Support\Facades\Http;

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

/** Pipeline
 *  Rotas de UPLOAD => Rotas de Selecionar Tabelas => Rotas de Editar Tabela => Rotas de Display do Gráfico Relacionado
 */

Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
Route::post('/upload-dataset', [UploadController::class, 'receive_dataset'])->name('upload.upload');

Route::get('/select-table',  [TableController::class, 'index'])->name('table.index');
Route::get('/display-table', [TableController::class, 'display'])->name('table.display');

Route::get('/form-edit-data/{name}', [ManipulateDataController::class, 'index'])->name('csv.edit');
Route::post('/form-edit-data/update', [ManipulateDataController::class, 'update'])->name('csv.update');

Route::get('/graph', [GraphController::class, 'retrieve_graph'])->name('graph.retrieve');

Route::get('/regras-validacao/{file_name}', [ValidationController::class, 'index'])->name('validation.index');
Route::post('/regras-validacao', [ValidationController::class, 'validation'])->name('validation.validation');

Route::get('/filtro', [FilterController::class, 'index'])->name('filter.index');
Route::get('/filtro/filter', [FilterController::class, 'filter_values'])->name('filter.filter');

Route::get('/lancamento', [LaunchController::class, 'index'])->name('launch.index');
Route::post('/lancamento/lancar', [LaunchController::class, 'launch'])->name('launch.launch');