<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::where('symbol', 'BRL')->first();

        $account = new Account();
        $account->name = 'Nu Bank';
        $account->mainCurrency()->associate($currency);
        $account->save();

        $account = new Account();
        $account->name = 'Mobills';
        $account->mainCurrency()->associate($currency);
        $account->save();
    }
}