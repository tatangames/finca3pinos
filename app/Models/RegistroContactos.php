<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroContactos extends Model
{
    use HasFactory;
    protected $table = 'registro_contactos';
    public $timestamps = false;

    protected $fillable = ['fecha', 'nombre', 'correo', 'mensaje'];
}
