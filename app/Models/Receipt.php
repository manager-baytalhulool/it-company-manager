<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $dates = ['date'];
    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;

        $query->join('projects', 'receipts.project_id', '=', 'projects.id')
            ->join('accounts', 'projects.account_id', '=', 'accounts.id')
            ->where(function ($q) use ($searchTerm) {
                $q->where('receipts.description', 'like', "%{$searchTerm}%")
                    ->orWhere('projects.name', 'like', "%{$searchTerm}%")
                    ->orWhere('accounts.name', 'like', "%{$searchTerm}%");
            })
            ->select('receipts.*');
    }
}
