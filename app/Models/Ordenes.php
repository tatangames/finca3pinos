<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordenes extends Model
{
    use HasFactory;
    protected $table = 'ordenes';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'ern',
        'fecha',

        // Envío
        'shipping_nombre',
        'shipping_telefono',
        'shipping_pais',
        'shipping_estado',
        'shipping_ciudad',
        'shipping_direccion',
        'shipping_zipcode',

        // Facturación
        'billing_nombre',
        'billing_direccion',
        'billing_ciudad',
        'billing_estado',
        'billing_zipcode',
        'billing_telefono',

        // Totales
        'subtotal',
        'shipping_cost',
        'total',

        // Estado y Pagadito
        'status',
        'pagadito_token',
        'pagadito_ref',
        'pagadito_status',
    ];

    // === Relaciones ===
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function items()
    {
        return $this->hasMany(OrdenesItem::class, 'id_orden');
    }
}
