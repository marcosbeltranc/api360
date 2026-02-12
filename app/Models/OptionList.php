<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Importar SoftDeletes

class OptionList extends Model
{
    use HasFactory, SoftDeletes; // 2. Usar el trait

    // 3. Definir los campos que se pueden llenar
    protected $fillable = [
        'name',
        'type',
        'slug',
        'color',
        'sort_order',
        'is_active',
    ];

    // 4. (Opcional) Casts para asegurar tipos de datos
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}