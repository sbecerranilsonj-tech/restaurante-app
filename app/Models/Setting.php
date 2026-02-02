<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Permitimos que se puedan guardar estas columnas masivamente
    protected $fillable = ['key', 'value'];
}