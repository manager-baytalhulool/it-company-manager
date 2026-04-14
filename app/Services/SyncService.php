<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Project;
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
    }

    protected function info($message)
    {
        if ($this->command) {
            $this->command->info($message);
        }
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
                'original_amount' => $oldProject->original_amount,
                'paid' => $oldProject->paid,
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

    public function setCommand(?Command $command): self
    {
        $this->command = $command;
        return $this;
    }
}
