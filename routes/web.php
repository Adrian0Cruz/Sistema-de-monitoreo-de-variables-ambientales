<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if (! function_exists('getAetherSensSettings')) {
    function getAetherSensSettings(): array
    {
        $default = [
            'tempMin' => 18.0,
            'tempMax' => 24.0,
            'tempCritica' => 28.0,
            'humMin' => 40.0,
            'humMax' => 60.0,
            'humCritica' => 75.0,
            'humoPpm' => 50,
            'brokerHost' => 'broker.hivemq.com',
            'brokerPort' => 8884,
            'topic' => 'pas_iot/datacenter/telemetria',
            'intervalo' => 3,
            'watchdog' => 10,
            'zona' => 'Zona A - Sala de Racks (Montería)',
            'audioAlert' => true,
            'auditLog' => true,
        ];

        if (Storage::exists('settings.json')) {
            try {
                $stored = json_decode(Storage::get('settings.json'), true);
                if (is_array($stored)) {
                    return array_merge($default, $stored);
                }
            } catch (Throwable $e) {
                // Mantener configuración por defecto en caso de excepción
            }
        }

        return $default;
    }
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', function () {
    return view('docs');
})->name('docs');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $settings = getAetherSensSettings();

        return view('dashboard', compact('settings'));
    })->name('dashboard');

    Route::get('/historical-data', function () {
        return view('historical-data');
    })->name('historical-data');

    Route::get('/alert-logs', function () {
        return view('alert-logs');
    })->name('alert-logs');

    Route::get('/settings', function () {
        $settings = getAetherSensSettings();

        return view('settings', compact('settings'));
    })->name('settings');

    Route::post('/settings', function (Request $request) {
        $validated = $request->validate([
            'tempMin' => 'required|numeric',
            'tempMax' => 'required|numeric',
            'tempCritica' => 'required|numeric',
            'humMin' => 'required|numeric',
            'humMax' => 'required|numeric',
            'humCritica' => 'required|numeric',
            'humoPpm' => 'required|numeric',
            'brokerHost' => 'required|string',
            'brokerPort' => 'required|numeric',
            'topic' => 'required|string',
            'intervalo' => 'required|numeric',
            'watchdog' => 'required|numeric',
            'zona' => 'required|string',
            'audioAlert' => 'nullable|boolean',
            'auditLog' => 'nullable|boolean',
        ]);

        $validated['tempMin'] = (float) $validated['tempMin'];
        $validated['tempMax'] = (float) $validated['tempMax'];
        $validated['tempCritica'] = (float) $validated['tempCritica'];
        $validated['humMin'] = (float) $validated['humMin'];
        $validated['humMax'] = (float) $validated['humMax'];
        $validated['humCritica'] = (float) $validated['humCritica'];
        $validated['humoPpm'] = (int) $validated['humoPpm'];
        $validated['brokerPort'] = (int) $validated['brokerPort'];
        $validated['intervalo'] = (int) $validated['intervalo'];
        $validated['watchdog'] = (int) $validated['watchdog'];
        $validated['audioAlert'] = (bool) ($request->input('audioAlert', true));
        $validated['auditLog'] = (bool) ($request->input('auditLog', true));

        Storage::put('settings.json', json_encode($validated, JSON_PRETTY_PRINT));

        return response()->json([
            'status' => 'success',
            'message' => 'Configuración guardada exitosamente.',
            'data' => $validated,
        ]);
    })->name('settings.save');
});
