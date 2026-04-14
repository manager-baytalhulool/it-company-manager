<?php

namespace App\Console\Commands;

use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-data
                            {modules?* : Sync modules to run (e.g. accounts projects)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from old database to new database';

    public function __construct(private SyncService $sync)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $available = ['accounts', 'projects'];
        $selected = $this->argument('modules');

        $this->sync->setCommand($this);

        // No arguments = run all
        if (empty($selected)) {
            $this->info('Starting all syncs...');
            // $this->sync->all();
            $this->sync->all();
            $this->info('All syncs completed.');
            return self::SUCCESS;
        }

        // Validate
        foreach ($selected as $module) {
            if (!in_array($module, $available)) {
                $this->error("Unknown module: {$module}. Available: " . implode(', ', $available));
                return self::FAILURE;
            }
        }

        // Run selected
        $this->info('Starting selected syncs...');
        foreach ($selected as $method) {
            // $this->info("  Syncing {$method}...");
            $this->sync->$method();
        }
        $this->info('Selected syncs completed.');
        return self::SUCCESS;
    }
}
