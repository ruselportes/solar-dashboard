
<?php

use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chartData');
Route::get('/logs', [DashboardController::class, 'logs'])->name('logs');