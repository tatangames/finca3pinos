<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroContactos extends Model
{
    use HasFactory;
    protected $table = 'registro_contactos';
    public $timestamps = false;



    protected $fillable = ['id_paises', 'fecha', 'nombre', 'correo', 'telefono', 'mensaje', 'tipo_formulario'];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_paises');
    }
}
