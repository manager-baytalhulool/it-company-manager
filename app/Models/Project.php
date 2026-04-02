<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query
            ->leftJoin('accounts', 'projects.account_id', '=', 'accounts.id')
            ->where('projects.name', 'like', "%{$searchTerm}%")
            ->orWhere('projects.live_url', 'like', "%{$searchTerm}%")
            ->orWhere('accounts.name', 'like', "%{$searchTerm}%");
    }


    public function repositories()
    {
        return $this->morphMany(Repository::class, 'repositable');
    }
}
