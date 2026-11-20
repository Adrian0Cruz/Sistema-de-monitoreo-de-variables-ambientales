<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelemetryLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'dispositivo',
        'sala_id',
        'zona',
        'temperatura',
        'humedad',
        'sensacion_termica',
        'gas',
        'estado',
        'silenciado',
        'uptime_s',
        'created_at',
    ];

    protected $casts = [
        'temperatura' => 'float',
        'humedad' => 'float',
        'sensacion_termica' => 'float',
        'gas' => 'integer',
        'silenciado' => 'boolean',
        'uptime_s' => 'integer',
        'created_at' => 'datetime',
    ];
}
