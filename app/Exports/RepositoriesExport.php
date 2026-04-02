<?php

namespace App\Exports;

use App\Models\Repository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;


class RepositoriesExport implements FromCollection, WithStrictNullComparison
{
    public function collection()
    {
        return Repository::with('repositable')->get()->map(function ($repository) {
            return [
                'parent_type' => $repository->repositable_type === 'App\\Models\\Project' ? 'Project' : 'Product',
                'parent_name' => $repository->repositable?->name ?? 'N/A',
                'name' => $repository->name,
                'url' => $repository->url,
                'provider' => $repository->provider,
                'is_private' => $repository->is_private,
            ];
        });
    }
}
