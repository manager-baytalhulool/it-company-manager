<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function forJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'for_account_head_id');
    }
}
