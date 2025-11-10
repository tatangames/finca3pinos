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
        'id_paises',
        'id_departamentos',
        'id_municipios',

        // Envío
        'shipping_nombre',
        'shipping_direccion',
        'shipping_ciudad',
        'shipping_direccion_opc',
        'shipping_estado',
        'shipping_zipcode',
        'shipping_telefono',

        // Facturación
        'billing_idpaises',
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
        'status_id',
        'pagadito_token',
        'pagadito_ref',
        'pagadito_status',

        'estado_pedido_1',
        'fecha_pedido_1',
        'estado_pedido_2',
        'fecha_pedido_2',
        'seguimiento',
        'visible_cliente'


    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_paises');
    }

    // === Relaciones ===
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function items()
    {
        return $this->hasMany(OrdenesItem::class, 'id_orden');
    }

    public function producto()
    {
        // Ajusta 'id_producto' y modelo Producto según tu estructura
        return $this->belongsTo(Producto::class, 'id_producto', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(OrdenesItem::class, 'id_orden');
    }

    public const STATUS = [
        1 => 'pending',
        2 => 'paid',
        3 => 'failed',
        4 => 'canceled',
        5 => 'refunded',
    ];

    public function getStatusNameAttribute(): string
    {
        return self::STATUS[$this->status_id] ?? 'unknown';
    }
}
