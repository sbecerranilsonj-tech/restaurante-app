<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'area_id', 'x_pos', 'y_pos', 'width', 'height', 'status'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ESTA ES LA FUNCIÓN QUE FALTABA
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}