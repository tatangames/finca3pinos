<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionFacturacion extends Model
{
    use HasFactory;
    protected $table = 'direcciones_facturacion';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_paises',
        'nombre',
        'direccion',
        'ciudad',
        'estado',
        'zipcode',
        'telefono',
    ];

}
