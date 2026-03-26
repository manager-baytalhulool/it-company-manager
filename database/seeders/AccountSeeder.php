<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = Currency::all();

        $accounts = [
            [
                'name' => 'Acme Corporation',
                'person' => 'John Doe',
                'phone' => '+1234567890',
                'currency_id' => $currencies->firstWhere('code', 'USD')?->id ?? 1,
                'address' => '123 Business St, New York, USA',
            ],
            [
                'name' => 'Tech Solutions Ltd',
                'person' => 'Jane Smith',
                'phone' => '+44123456789',
                'currency_id' => $currencies->firstWhere('code', 'GBP')?->id ?? 1,
                'address' => '456 Tech Ave, London, UK',
            ],
            [
                'name' => 'Global Enterprises',
                'person' => 'Ahmed Hassan',
                'phone' => '+971501234567',
                'currency_id' => $currencies->firstWhere('code', 'AED')?->id ?? 1,
                'address' => '789 Global Tower, Dubai, UAE',
            ],
            [
                'name' => 'Euro Tech GmbH',
                'person' => 'Hans Mueller',
                'phone' => '+4915123456789',
                'currency_id' => $currencies->firstWhere('code', 'EUR')?->id ?? 1,
                'address' => '321 Industry Rd, Berlin, Germany',
            ],
            [
                'name' => 'Pakistan Software House',
                'person' => 'Ali Khan',
                'phone' => '+923001234567',
                'currency_id' => $currencies->firstWhere('code', 'PKR')?->id ?? 1,
                'address' => '654 IT Park, Karachi, Pakistan',
            ],
        ];

        foreach ($accounts as $accountData) {
            Account::create(array_merge($accountData, [
                'balance' => 0,
                'amount' => 0,
                'original_amount' => 0,
                'projects_count' => 0,
            ]));
        }
    }
}
