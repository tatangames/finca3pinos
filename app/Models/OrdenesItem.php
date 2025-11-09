<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenesItem extends Model
{
    use HasFactory;
    protected $table = 'ordenes_items';
    public $timestamps = true;

    protected $fillable = [
        'id_orden',
        'id_producto',
        'nombre',
        'precio',
        'cantidad',
        'subtotal',
    ];

    // === Relaciones ===
    public function orden()
    {
        return $this->belongsTo(Ordenes::class, 'id_orden');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
