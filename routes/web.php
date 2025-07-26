<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeputadoController;
use App\Http\Controllers\DespesaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/deputados', [DeputadoController::class, 'index'])->name('deputados.index');
Route::get('/consulta-despesas', [DespesaController::class, 'index']);
