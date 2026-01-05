<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
}
