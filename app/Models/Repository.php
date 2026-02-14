<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repository extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query
            ->join('projects', 'projects.id', '=', 'repositories.project_id')
            ->where('repositories.name', 'like', "%{$searchTerm}%")
            ->orWhere('repositories.url', 'like', "%{$searchTerm}%")
            ->orWhere('repositories.provider', 'like', "%{$searchTerm}%")
            ->orWhere('projects.name', 'like', "%{$searchTerm}%");
    }
}
