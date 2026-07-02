<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncService
{
    protected ?Command $command = null;

    public function accounts(): void
    {
        $this->info("accounts sync started...");
        $ignoreableIds = [19];
        $oldAccounts = DB::connection('mysql_old')->table('accounts')->get();
        foreach ($oldAccounts as $oldAccount) {
            if (in_array($oldAccount->id, $ignoreableIds)) {
                continue;
            }
            $existingAccount = Account::where('name', $oldAccount->name)->first();
            if ($existingAccount) {
                continue;
            }
            Account::create([
                'name' => $oldAccount->name,
                'person' => $oldAccount->person,
                'phone' => $oldAccount->phone,
                'currency_id' => $oldAccount->currency_id,
                'parent_id' => $oldAccount->parent_id,
                'address' => $oldAccount->address,
                'latitude' => $oldAccount->latitude,
                'longitude' => $oldAccount->longitude,
                'created_at' => $oldAccount->created_at,
                'updated_at' => $oldAccount->updated_at,
            ]);
        }
        $this->info("accounts sync completed.");
    }

    public function all(): void
    {
        $this->accounts();
        $this->projects();
        $this->invoices();
        $this->receipts();
    }

    protected function info($message)
    {
        if ($this->command) {
            $this->command->info($message);
        }
    }

    public function invoices(): void
    {
        $this->info("invoices sync started...");
        $oldInvoices = DB::connection('mysql_old')->table('invoices')->get();
        foreach ($oldInvoices as $oldInvoice) {
            $existingInvoice = Invoice::where('description', $oldInvoice->description)
                ->where('amount', $oldInvoice->amount)
                ->where('date', $oldInvoice->date)
                ->first();
            if ($existingInvoice) {
                continue;
            }
            $oldProject = DB::connection('mysql_old')
                ->table('projects')
                ->where('id', $oldInvoice->project_id)
                ->first();
            $oldCurrency = DB::connection('mysql_old')
                ->table('currencies')
                ->where('id', $oldInvoice->currency_id)
                ->first();
            $project = Project::where('name', $oldProject->name)->first();
            if (!$project) {
                Log::error("Project not found for invoice: " . $oldInvoice->id);
                continue;
            }
            $currency = Currency::where('code', $oldCurrency->currency)->first();
            if (!$currency) {
                Log::error("Currency not found for invoice: " . $oldInvoice->id);
                continue;
            }
            Invoice::create([
                'project_id' => $project->id,
                'currency_id' => $currency->id,
                'date' => $oldInvoice->date,
                'due_date' => $oldInvoice->due_date,
                'description' => $oldInvoice->description,
                'amount' => $oldInvoice->amount,
                'status' => $oldInvoice->status,
                'created_at' => $oldInvoice->created_at,
                'updated_at' => $oldInvoice->updated_at,
                'deleted_at' => $oldInvoice->deleted_at,
            ]);
        }
        $this->info("invoices sync completed.");
    }

    public function projects(): void
    {
        $this->info("projects sync started...");
        $ignoreableIds = [1, 2, 19, 20, 27, 28, 29, 34, 35];
        $oldProjects = DB::connection('mysql_old')
            ->table('projects')
            ->get();

        foreach ($oldProjects as $oldProject) {
            if (in_array($oldProject->id, $ignoreableIds)) {
                continue;
            }

            // Check if project already exists
            $existingProject = Project::where("name", $oldProject->name)->first();
            if ($existingProject) {
                continue; // Skip existing projects
            }

            $oldProjectAccountId = $oldProject->account_id;
            if ($oldProjectAccountId == 19) {
                $oldProjectAccountId = 17;
            }

            $oldAccount = DB::connection('mysql_old')
                ->table('accounts')
                ->where('id', $oldProjectAccountId)
                ->first();



            $account = Account::where('name', $oldAccount->name)->first();
            $currency = Currency::where('code', $oldAccount->currency)->first();

            if (!$account) {
                dd($oldAccount);
            }

            if (!$currency) {
                dd($oldAccount);
            }

            // Migrate project
            Project::create([
                'account_id' => $account->id,
                'currency_id' => $currency->id,
                'name' => $oldProject->name,
                'amount' => $oldProject->amount,
                'original_amount' => 0, // $oldProject->original_amount // will be handled while syncing receipts
                'paid' => 0, // $oldProject->paid // will be handled while syncing receipts
                'is_available' => false,
                'is_duplicable' => false,
                'is_sellable' => false,
                'live_url' => "",
                'demo_url' => "",
                'started_at' => $oldProject->created_at,
                'is_live' => false,
                'created_at' => $oldProject->created_at,
                'updated_at' => $oldProject->updated_at,
            ]);
        }
        $this->info("projects sync completed.");
    }

    public function receipts(): void
    {
        $this->info("receipts sync started...");
        $oldReceipts = DB::connection('mysql_old')
            ->table('receipts')
            ->get();

        $ignoreableProjectIds = [1, 2, 19, 20, 27, 28, 29, 34, 35];

        foreach ($oldReceipts as $oldReceipt) {
            if (in_array($oldReceipt->project_id, $ignoreableProjectIds)) {
                continue;
            }

            $existingReceipt = Receipt::where('description', $oldReceipt->description)
                ->where('amount', $oldReceipt->amount)
                ->where('date', $oldReceipt->date)
                ->where('original_amount', $oldReceipt->original_amount)
                ->first();
            if ($existingReceipt) {
                continue;
            }

            $oldProject = DB::connection('mysql_old')
                ->table('projects')
                ->where('id', $oldReceipt->project_id)
                ->first();
            $project = Project::where('name', $oldProject->name)->first();
            if (!$project) {
                Log::error("Project not found for receipt: " . $oldReceipt->id);
                dd($oldProject);
            }

            $oldInvoice = DB::connection('mysql_old')
                ->table('invoices')
                ->where('id', $oldReceipt->invoice_id)
                ->first();
            $invoice = Invoice::where('description', $oldInvoice->description)
                ->where('amount', $oldInvoice->amount)
                ->where('date', $oldInvoice->date)
                ->first();
            if (!$invoice) {
                Log::error("Invoice not found for receipt: " . $oldReceipt->id);
                dd($oldInvoice);
            }

            DB::beginTransaction();
            $receipt = Receipt::create([
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'date' => $oldReceipt->date,
                'description' => $oldReceipt->description,
                'amount' => $oldReceipt->amount,
                'original_amount' => $oldReceipt->original_amount,
                'created_at' => $oldReceipt->created_at,
                'updated_at' => $oldReceipt->updated_at,
                'deleted_at' => null, // $oldReceipt->deleted_at // deleted_at does not exist on old table
            ]);

            // Invoice status updated while syncing invoices
            // $invoice = Invoice::find($request->invoice_id);
            // $invoice->status = InvoiceStatus::PAID->value;
            // $invoice->save();

            /** project paid & original_amount synced while syncing projects  */
            // $project = Project::withTrashed()->find($invoice->project_id);
            // // new column for this
            $project->original_amount += $receipt->original_amount; // original paid amount
            $project->paid += $receipt->amount;
            $project->save();

            $account = Account::find($project->account_id);
            $account->amount += $receipt->amount;
            $account->original_amount += $receipt->original_amount;
            $account->save();

            $journalEntryService = new JournalEntryService();
            $journalEntryService->createJournalEntryOnReceipt($receipt);

            DB::commit();
        }

        $this->info("receipts sync completed.");
    }

    public function setCommand(?Command $command): self
    {
        $this->command = $command;
        return $this;
    }
}
