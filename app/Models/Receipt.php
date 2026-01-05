<?php

namespace App\Models;

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
}
