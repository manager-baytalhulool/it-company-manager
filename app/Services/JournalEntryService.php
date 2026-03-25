<?php

namespace App\Services;

use App\Models\AccountHead;
use App\Models\JournalEntry;
use App\Models\JournalEntrySerialNumber;
use App\Models\Receipt;

class JournalEntryService
{
    public function getSerialNumber()
    {
        return JournalEntrySerialNumber::create();
    }

    public function createJournalEntry(JournalEntrySerialNumber $journalEntrySerialNumber, $accountHeadId, $forAccountHeadId, $debit, $credit, $date, $referenceType = null, $referenceId = null)
    {
        return JournalEntry::create([
            'journal_entry_serial_number_id' => $journalEntrySerialNumber->id,
            'account_head_id' => $accountHeadId,
            'for_account_head_id' => $forAccountHeadId,
            'debit' => $debit,
            'credit' => $credit,
            'date' => $date,

            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    public function createJournalEntryOnReceipt($receipt)
    {
        $serialNumber = $this->getSerialNumber();
        // $receipt->project->account_id

        $this->createJournalEntry(
            $serialNumber,
            AccountHead::CASH_ID,
            AccountHead::SALE_ID,
            $receipt->amount,
            0,
            $receipt->date,
            Receipt::class,
            $receipt->id
        );

        $this->createJournalEntry(
            $serialNumber,
            AccountHead::SALE_ID,
            AccountHead::CASH_ID,
            0,
            $receipt->amount,
            $receipt->date,
            Receipt::class,
            $receipt->id
        );
    }
}
