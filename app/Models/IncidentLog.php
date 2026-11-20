<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'dispositivo',
        'sala_id',
        'zona',
        'sensor',
        'valor',
        'umbral',
        'severidad',
        'evento',
        'estado',
        'reconocido_por',
        'reconocido_at',
        'created_at',
    ];

    protected $casts = [
        'reconocido_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
