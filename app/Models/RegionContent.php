<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionContent extends Model
{
    public $timestamps = false;
    protected $fillable = ['region_id','key','title','body','status','published_at','updated_by'];
    public function region() {
        return $this->belongsTo(Region::class);
    }
}
