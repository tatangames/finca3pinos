<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionContentTranslation extends Model
{
    protected $table = 'region_content_translation'; // ← singular
    public $timestamps = false; // porque no los creaste
    protected $fillable = ['content_id','locale','body'];

    public function content()
    {
        return $this->belongsTo(RegionContent::class, 'content_id');
    }

}
