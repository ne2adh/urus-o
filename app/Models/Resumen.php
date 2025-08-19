<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resumen extends Model
{
    protected $table = 'resumen';

    protected $fillable = [
        'fecha',
        'numero_dia',
        'user_id',
        'provincia',
        'municipio',
        'circunscripcion',
        'total_dia',
        'total_dia_prov',
        'total_dia_mun',
        'total_dia_circ',
        'acum_user',
        'acum_user_prov',
        'acum_user_mun',
        'acum_user_circ',
        'porc_meta_user',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha' => 'date',
        'porc_meta_user' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
