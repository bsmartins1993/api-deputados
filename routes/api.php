<?php

use App\Http\Controllers\ApiDeputadoController;
use App\Http\Controllers\ApiDespesaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('deputados', ApiDeputadoController::class);
Route::apiResource('despesas', ApiDespesaController::class);

// Para despesas de um deputado específico:
Route::get('deputados/{id}/despesas', [ApiDeputadoController::class, 'despesas']);
Route::post('deputados/importar', [ApiDeputadoController::class, 'importarExternos']);
Route::post('deputados/{id_deputado}/{id_camara}/importar-despesas', [ApiDespesaController::class, 'importarExternos']);
