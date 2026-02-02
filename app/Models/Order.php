<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table_id', 
        'user_id', 
        'client_id', // <--- NUEVO
        'status', 
        'total', 
        'payment_method', 
        'received_amount', 
        'change_amount',
        'document_type',
        'client_name',     // Se mantiene como respaldo histórico
        'client_document', // Se mantiene como respaldo histórico
        'discount',
        'tip'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Nueva relación
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}