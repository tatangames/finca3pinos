<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosPresentacion extends Model
{
    use HasFactory;
    protected $table = 'productos_presentacion';
    public $timestamps = false;

    protected $fillable = ['posicion'];
}
