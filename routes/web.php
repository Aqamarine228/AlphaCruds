<?php

use AlphaDevTeam\AlphaCruds\Http\Controllers\CurdGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CurdGeneratorController::class, 'main'])->name('main');
Route::post('/', [CurdGeneratorController::class, 'create'])->name('create');
