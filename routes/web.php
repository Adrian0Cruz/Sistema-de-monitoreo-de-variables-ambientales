<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if (! function_exists('getAetherSensSettings')) {
    function getAetherSensSettings(): array
    {
        // Los valores por defecto replican las constantes del firmware del ESP32
        // (TEMP_UMBRAL_ADVERTENCIA, TEMP_UMBRAL_CRITICO, MQ2_UMBRAL_HUMO y los
        // tópicos MQTT declarados en el sketch de Intalnet).
        $default = [
            'tempMin' => 18.0,
            'tempMax' => 27.0,
            'tempCritica' => 31.0,
            'humMin' => 40.0,
            'humMax' => 60.0,
            'humCritica' => 75.0,
            'gasAdcUmbral' => 3300,
            'brokerHost' => 'broker.hivemq.com',
            'brokerPort' => 1883,
            'brokerWsPort' => 8000,
            'brokerWsPortTls' => 8884,
            'topic' => 'intalnet/sala01/telemetria',
            'topicEstado' => 'intalnet/sala01/estado',
            'topicAlerta' => 'intalnet/sala01/alerta',
            'topicConfig' => 'intalnet/sala01/config',
            'intervalo' => 5,
            'watchdog' => 10,
            'zona' => 'Sala 01 - Intalnet (Montería)',
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

// Registro de operadores: restringido exclusivamente a usuarios autenticados (404 para invitados externos)
Route::get('/register', function () {
    if (! auth()->check()) {
        abort(404);
    }

    $operadores = User::latest()->take(10)->get();

    return view('auth.register-operator', compact('operadores'));
})->name('register');

Route::post('/register', function (Request $request, CreateNewUser $creator) {
    if (! auth()->check()) {
        abort(404);
    }

    $user = $creator->create($request->all());

    return back()->with('status', "Operador '{$user->name}' ({$user->email}) registrado exitosamente con nivel {$user->access_level_label}.");
})->name('register.store');

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
        $settings = getAetherSensSettings();

        return view('historical-data', compact('settings'));
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
            'gasAdcUmbral' => 'required|numeric',
            'brokerHost' => 'required|string',
            'brokerPort' => 'required|numeric',
            'brokerWsPort' => 'required|numeric',
            'brokerWsPortTls' => 'required|numeric',
            'topic' => 'required|string',
            'topicEstado' => 'required|string',
            'topicAlerta' => 'required|string',
            'topicConfig' => 'nullable|string',
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
        $validated['gasAdcUmbral'] = (int) $validated['gasAdcUmbral'];
        $validated['brokerPort'] = (int) $validated['brokerPort'];
        $validated['brokerWsPort'] = (int) $validated['brokerWsPort'];
        $validated['brokerWsPortTls'] = (int) $validated['brokerWsPortTls'];
        $validated['intervalo'] = (int) $validated['intervalo'];
        $validated['watchdog'] = (int) $validated['watchdog'];
        $validated['topicConfig'] = (string) ($request->input('topicConfig', 'intalnet/sala01/config'));
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
