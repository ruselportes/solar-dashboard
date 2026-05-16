
<?php

use App\Http\Controllers\Api\SensorLogController;
use App\Http\Controllers\Api\PowerReadingController;
use App\Http\Controllers\Api\SystemEventController;
use App\Http\Controllers\Api\SystemConfigController;
use App\Http\Controllers\Api\ManualCommandController;
use App\Http\Controllers\Api\UploadStatusController;

// System Config
Route::get('system-configs/latest', [SystemConfigController::class, 'latest']);
Route::post('system-configs', [SystemConfigController::class, 'store']);

// Sensor Logs
Route::get('sensor-logs/latest', [SensorLogController::class, 'latest']);
Route::get('sensor-logs', [SensorLogController::class, 'index']);
Route::post('sensor-logs', [SensorLogController::class, 'store']);

// Power Readings
Route::get('power-readings/latest', [PowerReadingController::class, 'latest']);
Route::post('power-readings', [PowerReadingController::class, 'store']);

// System Events
Route::get('system-events/recent', [SystemEventController::class, 'recent']);
Route::post('system-events', [SystemEventController::class, 'store']);

// Manual Commands
Route::post('manual-commands', [ManualCommandController::class, 'store']);

// Upload Statuses
Route::post('upload-statuses', [UploadStatusController::class, 'store']);