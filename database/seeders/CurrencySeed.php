<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = new Currency();
        $currency->name = 'US Dollar';
        $currency->symbol = 'USD';
        $currency->format = '$ {amount}';
        $currency->save();

        $currency = new Currency();
        $currency->name = 'Euro';
        $currency->symbol = 'EUR';
        $currency->format = '€ {amount}';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();

        $currency = new Currency();
        $currency->name = 'Brazilian Real';
        $currency->symbol = 'BRL';
        $currency->format = 'R$ {amount}';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();

        $currency = new Currency();
        $currency->name = 'British Pound';
        $currency->symbol = 'GBP';
        $currency->format = '£ {amount}';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();

        $currency = new Currency();
        $currency->name = 'Japanese Yen';
        $currency->symbol = 'JPY';
        $currency->format = '¥ {amount}';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();

        $currency = new Currency();
        $currency->name = 'Chinese Yuan';
        $currency->symbol = 'CNY';
        $currency->format = '¥ {amount}';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();

        $currency = new Currency();
        $currency->name = 'Bitcoin';
        $currency->symbol = 'BTC';
        $currency->format = '{amount} ₿';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();

        $currency = new Currency();
        $currency->name = 'Ethereum';
        $currency->symbol = 'ETH';
        $currency->format = '{amount} Ξ';
        $currency->parity()->associate(Currency::where('symbol', 'USD')->first());
        $currency->save();
    }
}