<?php

use AlphaDevTeam\AlphaCruds\Http\Controllers\CurdGeneratorController;
use AlphaDevTeam\AlphaCruds\Http\Controllers\TranslatedCrudGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/crud-generator', [CurdGeneratorController::class, 'index'])->name('crud-generator');
Route::post('/crud-generator', [CurdGeneratorController::class, 'create'])->name('crud-generator.create');

Route::get('/translated-crud-generator', [TranslatedCrudGeneratorController::class, 'index'])
    ->name('translated-crud-generator');
Route::post('/translated-crud-generator', [TranslatedCrudGeneratorController::class, 'create'])
    ->name('translated-crud-generator.create');
