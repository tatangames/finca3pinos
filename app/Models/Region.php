<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;
    protected $fillable = ['slug','name','locale'];
    public function contents() {
        return $this->hasMany(RegionContent::class);
    }
}
