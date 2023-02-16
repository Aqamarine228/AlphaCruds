<?php

use AlphaDevTeam\AlphaCruds\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
