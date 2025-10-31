<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;
    protected $table = 'order_addresses';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'type',             // <-- agrega esto
        'country',
        'country_code',
        'state',
        'city',
        'zipcode',
        'address_line',
        'name',
        'phone',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
