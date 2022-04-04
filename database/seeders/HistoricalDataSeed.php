<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;

class HistoricalDataSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usd = Currency::where('symbol', 'USD')->first();
        $eur = Currency::where('symbol', 'EUR')->first();
        $brl = Currency::where('symbol', 'BRL')->first();
        $gbp = Currency::where('symbol', 'GBP')->first();
        $jpy = Currency::where('symbol', 'JPY')->first();
        $cny = Currency::where('symbol', 'CNY')->first();
        $btc = Currency::where('symbol', 'BTC')->first();
        $eth = Currency::where('symbol', 'ETH')->first();

        $usd->historicalData()->create([
            'date' => '2022-01-01',
            'opening_value' => '1.0',
            'closing_value' => '1.0',
            'high_value' => '1.0',
            'low_value' => '1.0',
        ]);

        $csvFiles = Storage::allFiles('csv/HistoricalData');

        dd($csvFiles);
    }
}