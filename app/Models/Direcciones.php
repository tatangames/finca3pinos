<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direcciones extends Model
{
    use HasFactory;
    protected $table = 'direcciones';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_paises',
        'id_departamento',
        'id_municipio',
        'nombre',
        'direccion',
        'direccion_opcional',
        'ciudad',
        'estado',
        'zipcode',
        'telefono',
    ];
}
