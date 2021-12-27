<?php

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/getdb',[App\Http\Controllers\CRUDController::class, 'getDB'])->name('getdb');
Route::get('/tablas',[App\Http\Controllers\CRUDController::class, 'index'])->name('tablas');
Route::post('/tabla',[App\Http\Controllers\CRUDController::class, 'show'])->name('tabla');
Route::post('/consulta',[App\Http\Controllers\CRUDController::class, 'consulta'])->name('consulta');
Route::put('/tablas.edit', [App\Http\Controllers\CRUDController::class, 'edit'])->name('tablas.edit');
Route::post('/tabla.create', [App\Http\Controllers\CRUDController::class, 'create'])->name('tablas.create');
Route::post('/tablas.store', [App\Http\Controllers\CRUDController::class, 'store'])->name('tablas.store');
Route::put('/tabla.update', [App\Http\Controllers\CRUDController::class, 'update'] )->name('tabla.update');
Route::delete('/tabla.delete/{id}', [App\Http\Controllers\CRUDController::class, 'destroy'] )->name('tabla.delete');
Route::post('/excel', [App\Http\Controllers\CRUDController::class, 'exportexcel'])->name('excel');
