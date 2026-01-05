<?php

namespace App\Exports;

use App\Models\Repository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;


class RepositoriesExport implements FromCollection, WithStrictNullComparison
{
    public function collection()
    {
        return Repository::join('projects', 'repositories.project_id', '=', 'projects.id')
            ->select(
                'projects.name as project_name',
                'repositories.name', 
                'repositories.url', 
                'repositories.provider', 
                'repositories.is_private'
            )
            ->get();
    }
}
