
<?php

use App\Http\Controllers\Api\SensorLogController;
use App\Http\Controllers\Api\PowerReadingController;

Route::get('sensor-logs/latest', [SensorLogController::class, 'latest']);
Route::get('sensor-logs', [SensorLogController::class, 'index']);
// Add similar for power-readings