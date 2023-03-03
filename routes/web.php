<?php

use Aqamarine\AlphaCruds\Http\Controllers\ApiCrudCrudGeneratorController;
use Aqamarine\AlphaCruds\Http\Controllers\CurdCrudGeneratorController;
use Aqamarine\AlphaCruds\Http\Controllers\TranslatedCrudCrudGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/crud-generator', [CurdCrudGeneratorController::class, 'index'])->name('crud-generator');
Route::post('/crud-generator', [CurdCrudGeneratorController::class, 'create'])->name('crud-generator.create');

Route::get('/translated-crud-generator', [TranslatedCrudCrudGeneratorController::class, 'index'])
    ->name('translated-crud-generator');
Route::post('/translated-crud-generator', [TranslatedCrudCrudGeneratorController::class, 'create'])
    ->name('translated-crud-generator.create');

Route::get('/api-crud-generator', [ApiCrudCrudGeneratorController::class, 'index'])
    ->name('api-crud-generator');
Route::post('/api-crud-generator', [ApiCrudCrudGeneratorController::class, 'create'])
    ->name('api-crud-generator.create');
