<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    protected $guarded = ['id'];

    const CASH_ID = 1;
    const SALE_ID = 2;

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function forJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'for_account_head_id');
    }
}
