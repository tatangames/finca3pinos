<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = 'orders_items';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'purchasable_id', 'purchasable_type',  // morphs
        'sku',
        'name',
        'presentation',
        'qty',
        'unit_price',
        'discount',
        'tax',
        'total',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function order(){ return $this->belongsTo(Order::class); }

    // Si luego necesitas acceder al producto:
    public function purchasable(){ return $this->morphTo(); }
}
