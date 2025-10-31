<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'user_id',
        'currency',
        'sub_total',
        'shipping_total',
        'grand_total',
        'status',
        'pay_gateway',
        'pay_token',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'meta'     => 'array',
        'paid_at'  => 'datetime',
    ];

    // === Relaciones ===
    public function items(){ return $this->hasMany(OrderItems::class, 'order_id'); }

    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class)->where('type','shipping');
    }

    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class)->where('type','billing');
    }


    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
