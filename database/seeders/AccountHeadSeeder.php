<?php

namespace Database\Seeders;

use App\Models\AccountHead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            [
                'name' => 'Cash',
            ],
            [
                'name' => 'Sales',
            ],
            [
                'name' => 'Capital',
            ],
            [
                'name' => 'Purchase',
            ],
            [
                'name' => 'Account Payable',
            ],
            [
                'name' => 'Account Receivable',
            ],
        ];


        foreach ($arr as $record) {
            AccountHead::create($record);
        }
    }
}
