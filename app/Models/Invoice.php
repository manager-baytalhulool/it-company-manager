<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $dates = ['date', 'due_date'];
    protected $guarded = ['id'];


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
