<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function generalEntries()
    {
        return $this->hasMany(GeneralEntry::class);
    }

    public function forGeneralEntries()
    {
        return $this->hasMany(GeneralEntry::class, 'for_account_head_id');
    }
}
