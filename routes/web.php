<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeputadoController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/deputados', [DeputadoController::class, 'index'])->name('deputados.index');
Route::get('/consulta-despesas', [DespesaController::class, 'consulta']);
Route::get('/deputados/autocomplete', [DeputadoController::class, 'autocomplete']);
Route::get('/dashboard', [DashboardController::class, 'index']);
