<?php

use App\Http\Controllers\TelemetryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoints de Ingestión y Consulta de Telemetría IoT (Alta Eficiencia y Escala)
Route::post('/telemetry', [TelemetryController::class, 'store'])->name('api.telemetry.store');
Route::get('/telemetry/history', [TelemetryController::class, 'history'])->name('api.telemetry.history');
Route::get('/telemetry/alerts', [TelemetryController::class, 'alerts'])->name('api.telemetry.alerts');
Route::post('/telemetry/alerts/{id}/ack', [TelemetryController::class, 'acknowledgeAlert'])->name('api.telemetry.alerts.ack');
Route::post('/telemetry/alerts/ack-all', [TelemetryController::class, 'acknowledgeAll'])->name('api.telemetry.alerts.ack_all');
