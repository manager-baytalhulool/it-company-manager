<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('description', 'like', "%{$searchTerm}%")
            ->orWhere('demo_url', 'like', "%{$searchTerm}%")
            ->orWhere('download_url', 'like', "%{$searchTerm}%");
    }
}
