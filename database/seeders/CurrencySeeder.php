<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ["name" => "Pakistani rupee", 'code' => 'PKR', "symbol" => "Rs.", 'exchange_rate' => 1, "is_base" => true],
            ["name" => "United States dollar", 'code' => 'USD', "symbol" => "$", 'exchange_rate' => 280, "is_base" => false],
            ["name" => "Euro", 'code' => 'EUR', "symbol" => "€", 'exchange_rate' => 330, "is_base" => false],
            ["name" => "United Arab Emirates dirham", 'code' => 'AED', "symbol" => "د. إ", 'exchange_rate' => 76, "is_base" => false],
            ["name" => "Sterling", 'code' => 'GBP', "symbol" => "£", 'exchange_rate' => 375, "is_base" => false],
        ];
        foreach ($currencies as $i => $currency) {
            Currency::create([
                "name" => $currency["name"],
                "code" => $currency["code"],
                "symbol" => $currency["symbol"],
                "exchange_rate" => $currency["exchange_rate"],
                "is_base" => $currency["is_base"],
            ]);
        }
    }
}
