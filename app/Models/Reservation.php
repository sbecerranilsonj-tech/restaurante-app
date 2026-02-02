<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'client_name', 'phone', 'reservation_time', 'people', 
        'table_id', 'note', 'status'
    ];

    protected $casts = [
        'reservation_time' => 'datetime'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}