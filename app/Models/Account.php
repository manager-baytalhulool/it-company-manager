<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('person', 'like', "%{$searchTerm}%");
    }
}
