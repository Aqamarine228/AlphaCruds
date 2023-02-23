<?php

use AlphaDevTeam\AlphaCruds\Http\Controllers\CurdGeneratorController;
use AlphaDevTeam\AlphaCruds\Http\Controllers\TranslatedCrudGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/crud-generator', [CurdGeneratorController::class, 'index'])->name('curd-generator');
Route::post('/crud-generator', [CurdGeneratorController::class, 'create'])->name('curd-generator.create');

Route::get('/translated-crud-generator', [TranslatedCrudGeneratorController::class, 'index'])
    ->name('translated-curd-generator');
Route::post('/translated-crud-generator', [TranslatedCrudGeneratorController::class, 'create'])
    ->name('translated-curd-generator.create');
