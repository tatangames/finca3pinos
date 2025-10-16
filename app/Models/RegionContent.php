<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionContent extends Model
{
    public $timestamps = false;
    protected $fillable = ['region_id','key','title','body','status','published_at','updated_by'];

    public function translations()
    {
        return $this->hasMany(RegionContentTranslation::class, 'content_id');
    }

    // helpers opcionales
    public function translation(string $locale, ?string $fallback = null)
    {
        $tr = $this->translations()->where('locale', $locale)->first();
        if ($tr) return $tr;

        if ($fallback) {
            return $this->translations()->where('locale', $fallback)->first();
        }
        return null;
    }
}
