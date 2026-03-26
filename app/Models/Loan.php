<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $guarded = ['id'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('description', 'like', "%{$searchTerm}%")
            ->orWhere('person', 'like', "%{$searchTerm}%")
            ->orWhere('status', 'like', "%{$searchTerm}%");
    }
}
