<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;


class ProjectsExport implements FromCollection, WithStrictNullComparison
{
    public function collection()
    {
        return Project::select('name', 'live_url', 'demo_url', 'started_at', 'is_live')->get();
    }
}
