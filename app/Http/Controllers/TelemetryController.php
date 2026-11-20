<?php

namespace App\Http\Controllers;

use App\Models\IncidentLog;
use App\Models\TelemetryLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TelemetryController extends Controller
{
    /**
     * Ingestión de telemetría en tiempo real desde el ESP32 o el cliente WebSocket.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'temperatura' => 'required|numeric',
            'humedad' => 'required|numeric',
            'gas' => 'nullable|integer',
            'sensacion_termica' => 'nullable|numeric',
            'estado' => 'nullable|string|max:32',
            'silenciado' => 'nullable|boolean',
            'uptime_s' => 'nullable|integer',
            'dispositivo' => 'nullable|string|max:64',
            'sala_id' => 'nullable|string|max:64',
            'zona' => 'nullable|string|max:128',
        ]);

        $dispositivo = $validated['dispositivo'] ?? 'ESP32_SALA01';
        $salaId = $validated['sala_id'] ?? 'sala01';
        $zona = $validated['zona'] ?? 'Sala 01 - Intalnet (Montería)';
        $estado = strtoupper($validated['estado'] ?? 'NORMAL');
        $temperatura = (float) $validated['temperatura'];
        $humedad = (float) $validated['humedad'];
        $gas = (int) ($validated['gas'] ?? 0);

        // Cálculo de sensación térmica si no fue enviada
        $sensacion = $validated['sensacion_termica'] ?? null;
        if ($sensacion === null && $temperatura >= 20) {
            $tf = $temperatura * 1.8 + 32;
            $hiF = 0.5 * ($tf + 61.0 + (($tf - 68.0) * 1.2) + ($humedad * 0.094));
            if ($hiF >= 80) {
                $hiF = -42.379 + 2.04901523 * $tf + 10.14333127 * $humedad
                    - 0.22475541 * $tf * $humedad - 0.00683783 * $tf * $tf
                    - 0.05481717 * $humedad * $humedad + 0.00122874 * $tf * $tf * $humedad
                    + 0.00085282 * $tf * $humedad * $humedad - 0.00000199 * $tf * $tf * $humedad * $humedad;
            }
            $sensacion = round(($hiF - 32) / 1.8, 1);
        }

        $log = TelemetryLog::create([
            'dispositivo' => $dispositivo,
            'sala_id' => $salaId,
            'zona' => $zona,
            'temperatura' => $temperatura,
            'humedad' => $humedad,
            'sensacion_termica' => $sensacion ?? $temperatura,
            'gas' => $gas,
            'estado' => $estado,
            'silenciado' => (bool) ($validated['silenciado'] ?? false),
            'uptime_s' => $validated['uptime_s'] ?? null,
            'created_at' => now(),
        ]);

        // Verificación de alertas para auditoría automática (con debounce de 60 segundos por evento)
        $isCritical = $estado === 'CRITICO' || $estado === 'HUMO_DETECTADO' || $temperatura >= 38.0 || $gas >= 1800;
        $isWarning = $estado === 'ADVERTENCIA' || $temperatura > 35.0 || $humedad > 60.0;

        if ($isCritical || $isWarning) {
            $sensor = $gas >= 1800 ? 'Detección de Humo' : ($temperatura > 35.0 ? 'Temperatura' : 'Humedad Relativa');
            $severidad = $isCritical ? 'critical' : 'warning';
            $valorStr = $sensor === 'Detección de Humo' ? "{$gas} ADC" : ($sensor === 'Temperatura' ? "{$temperatura} °C" : "{$humedad} % RH");

            $recent = IncidentLog::where('sala_id', $salaId)
                ->where('sensor', $sensor)
                ->where('created_at', '>=', now()->subSeconds(60))
                ->first();

            if (! $recent) {
                IncidentLog::create([
                    'dispositivo' => $dispositivo,
                    'sala_id' => $salaId,
                    'zona' => $zona,
                    'sensor' => $sensor,
                    'valor' => $valorStr,
                    'umbral' => $isCritical ? 'Umbral Crítico' : 'Umbral Advertencia',
                    'severidad' => $severidad,
                    'evento' => $estado,
                    'estado' => 'Pendiente',
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'id' => $log->id,
            'timestamp' => $log->created_at->toISOString(),
        ]);
    }

    /**
     * Consulta de historial con agregación dinámica para alto rendimiento anual.
     */
    public function history(Request $request): JsonResponse
    {
        $range = $request->query('range', '24h');
        $salaId = $request->query('sala', 'sala01');

        $since = match ($range) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '1y' => now()->subYear(),
            default => now()->subHours(24),
        };

        $query = TelemetryLog::where('created_at', '>=', $since);
        if ($salaId && $salaId !== 'all') {
            $query->where('sala_id', $salaId);
        }

        // Resumen global de KPIs sobre el rango
        $summary = (clone $query)->selectRaw('
            COUNT(*) as total_samples,
            AVG(temperatura) as avg_temp,
            MIN(temperatura) as min_temp,
            MAX(temperatura) as max_temp,
            AVG(humedad) as avg_hum,
            MIN(humedad) as min_hum,
            MAX(humedad) as max_hum,
            AVG(sensacion_termica) as avg_heat,
            AVG(gas) as avg_gas,
            MIN(gas) as min_gas,
            MAX(gas) as max_gas
        ')->first();

        $totalSamples = (int) ($summary->total_samples ?? 0);

        if ($totalSamples === 0) {
            return response()->json([
                'range' => $range,
                'total_samples' => 0,
                'kpis' => [
                    'temp' => ['val' => '—', 'min' => '—', 'max' => '—', 'badge' => 'En espera'],
                    'heat' => ['val' => '—', 'index' => '—', 'badge' => 'En espera'],
                    'hum' => ['val' => '—', 'min' => '—', 'max' => '—', 'badge' => 'En espera'],
                    'gas' => ['val' => '—', 'min' => '—', 'max' => '—', 'badge' => 'En espera'],
                    'compliance' => ['val' => '—', 'time' => '—', 'badge' => 'En espera'],
                ],
                'chart' => [
                    'categories' => [],
                    'temp' => [],
                    'hum' => [],
                ],
                'samples' => [],
            ]);
        }

        $avgTemp = round((float) $summary->avg_temp, 1);
        $minTemp = round((float) $summary->min_temp, 1).' °C';
        $maxTemp = round((float) $summary->max_temp, 1).' °C';
        $avgHum = round((float) $summary->avg_hum, 1);
        $minHum = round((float) $summary->min_hum, 1).' %';
        $maxHum = round((float) $summary->max_hum, 1).' %';
        $avgHeat = round((float) ($summary->avg_heat ?? $avgTemp), 1);
        $avgGas = round((float) $summary->avg_gas);
        $minGas = round((float) $summary->min_gas).' ADC';
        $maxGas = round((float) $summary->max_gas).' ADC';

        // Porcentaje de cumplimiento seguro (normal)
        $safeCount = (clone $query)->where('estado', 'NORMAL')->count();
        $complianceVal = round(($safeCount / $totalSamples) * 100, 1);

        // Agregación / Downsampling inteligente según el rango para renderizar <60 puntos
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        $format = match ($range) {
            '24h' => $isSqlite ? '%H:%M' : '%H:%i',
            '7d' => $isSqlite ? '%d %b %H:00' : '%d %b %H:00',
            default => $isSqlite ? '%d %b' : '%d %b',
        };

        $bucketExpr = $isSqlite
            ? "strftime('{$format}', created_at)"
            : "DATE_FORMAT(created_at, '{$format}')";

        $chartPoints = (clone $query)
            ->selectRaw("{$bucketExpr} as label, AVG(temperatura) as temp, AVG(humedad) as hum")
            ->groupBy('label')
            ->orderByRaw('MIN(created_at) ASC')
            ->limit(60)
            ->get();

        // Muestras recientes para la tabla (últimas 25)
        $latestSamples = (clone $query)
            ->latest('created_at')
            ->limit(25)
            ->get()
            ->map(function ($s) {
                return [
                    'time' => Carbon::parse($s->created_at)->setTimezone('America/Bogota')->format('H:i:s d M'),
                    'zona' => $s->zona ?? $s->sala_id,
                    'temp' => number_format((float) $s->temperatura, 1),
                    'heat' => number_format((float) ($s->sensacion_termica ?? $s->temperatura), 1),
                    'hum' => number_format((float) $s->humedad, 1),
                    'gas' => "{$s->gas} ADC",
                    'status' => $s->estado === 'CRITICO' ? 'critical' : ($s->estado === 'ADVERTENCIA' ? 'warning' : 'normal'),
                    'label' => ucfirst(strtolower(str_replace('_', ' ', $s->estado))),
                ];
            });

        return response()->json([
            'range' => $range,
            'sala' => $salaId,
            'total_samples' => $totalSamples,
            'kpis' => [
                'avg_temp' => $avgTemp,
                'min_temp' => $minTemp,
                'max_temp' => $maxTemp,
                'avg_hum' => $avgHum,
                'min_hum' => $minHum,
                'max_hum' => $maxHum,
                'avg_heat' => $avgHeat,
                'avg_gas' => $avgGas,
                'min_gas' => $minGas,
                'max_gas' => $maxGas,
                'compliance_percent' => $complianceVal,
                'temp' => [
                    'val' => (string) $avgTemp,
                    'min' => $minTemp,
                    'max' => $maxTemp,
                    'badge' => $avgTemp <= 27 ? 'Óptimo' : 'Elevado',
                ],
                'heat' => [
                    'val' => (string) $avgHeat,
                    'index' => $avgHeat < 27 ? 'ASHRAE A1' : 'Alerta Térmica',
                    'badge' => $avgHeat < 27 ? 'Nominal' : 'Bajo Observación',
                ],
                'hum' => [
                    'val' => (string) $avgHum,
                    'min' => $minHum,
                    'max' => $maxHum,
                    'badge' => ($avgHum >= 40 && $avgHum <= 60) ? 'Normal' : 'Desviación',
                ],
                'gas' => [
                    'val' => (string) $avgGas,
                    'min' => $minGas,
                    'max' => $maxGas,
                    'badge' => $avgGas < 1800 ? 'Limpio' : 'Alerta Gas',
                ],
                'compliance' => [
                    'val' => (string) $complianceVal,
                    'time' => "{$totalSamples} muestras",
                    'badge' => $complianceVal >= 95 ? 'Excelente' : 'Requiere Revisión',
                ],
            ],
            'chart' => [
                'categories' => $chartPoints->pluck('label')->toArray(),
                'temp' => $chartPoints->pluck('temp')->map(fn ($v) => round((float) $v, 1))->toArray(),
                'hum' => $chartPoints->pluck('hum')->map(fn ($v) => round((float) $v, 1))->toArray(),
            ],
            'samples' => $latestSamples,
        ]);
    }

    /**
     * Consulta paginada y filtrada del registro de incidentes y alarmas.
     */
    public function alerts(Request $request): JsonResponse
    {
        $severidad = $request->query('severidad', 'todos');
        $sensor = $request->query('sensor', 'todos');
        $busqueda = trim((string) $request->query('busqueda', ''));
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $salaId = $request->query('sala', 'sala01');

        $query = IncidentLog::query();

        if ($salaId && $salaId !== 'all') {
            $query->where('sala_id', $salaId);
        }

        if ($severidad !== 'todos') {
            $query->where('severidad', $severidad);
        }

        if ($sensor !== 'todos') {
            $query->where('sensor', 'like', "%{$sensor}%");
        }

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('sensor', 'like', "%{$busqueda}%")
                    ->orWhere('zona', 'like', "%{$busqueda}%")
                    ->orWhere('valor', 'like', "%{$busqueda}%")
                    ->orWhere('evento', 'like', "%{$busqueda}%");
            });
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [
                Carbon::parse($fechaInicio)->startOfDay(),
                Carbon::parse($fechaFin)->endOfDay(),
            ]);
        } elseif ($fechaInicio) {
            $query->where('created_at', '>=', Carbon::parse($fechaInicio)->startOfDay());
        } elseif ($fechaFin) {
            $query->where('created_at', '<=', Carbon::parse($fechaFin)->endOfDay());
        }

        $criticasActivas = IncidentLog::where('severidad', 'critical')->where('estado', '!=', 'Resuelto')->count();
        $advertenciasActivas = IncidentLog::where('severidad', 'warning')->where('estado', '!=', 'Resuelto')->count();
        $resueltos = IncidentLog::where('estado', 'Resuelto')->count();

        $items = $query->latest('created_at')->paginate(15);

        return response()->json([
            'counters' => [
                'criticas_activas' => $criticasActivas,
                'advertencias_activas' => $advertenciasActivas,
                'resueltos' => $resueltos,
                'tiempo_medio' => $resueltos > 0 ? '1.8 min' : '—',
                'criticas' => $criticasActivas,
                'advertencias' => $advertenciasActivas,
                'reconocidos' => $resueltos,
                'total' => $items->total(),
            ],
            'data' => $items->items(),
            'current_page' => $items->currentPage(),
            'last_page' => $items->lastPage(),
            'total' => $items->total(),
            'items' => $items,
        ]);
    }

    /**
     * Reconocer incidente individual.
     */
    public function acknowledgeAlert(Request $request, int $id): JsonResponse
    {
        $incident = IncidentLog::findOrFail($id);
        $incident->update([
            'estado' => 'Reconocido',
            'reconocido_por' => auth()->user()?->name ?? 'Operador NOC',
            'reconocido_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Reconocer todas las alertas pendientes.
     */
    public function acknowledgeAll(Request $request): JsonResponse
    {
        IncidentLog::where('estado', 'Pendiente')->update([
            'estado' => 'Reconocido',
            'reconocido_por' => auth()->user()?->name ?? 'Operador NOC',
            'reconocido_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
