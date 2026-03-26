<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id'];

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('description', 'like', "%{$searchTerm}%");
    }
}
