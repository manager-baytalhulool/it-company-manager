<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    protected $fillable = [
        'path',
        'alt_text',
        'is_featured',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }


    public function markAsFeatured()
    {
        return DB::transaction(function () {
            // 1. Pehle tamam images ka featured status khatam karein
            static::where('is_featured', true)->update(['is_featured' => false]);

            return $this->update(['is_featured' => true]);
        });
    }
}
