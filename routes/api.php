<?php

use App\Http\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::prefix('empresas')->controller(EmpresaController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::get('/nit/{nit}', 'showByNit');
    
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/inactivas', 'destroyInactive');
});

