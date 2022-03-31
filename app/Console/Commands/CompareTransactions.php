<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompareTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compare:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nubankFiles = collect(Storage::allFiles('json/NuBank'))->filter(function ($file) {
            return str_contains($file, 'All');
        });
        $mobillsFiles = collect(Storage::allFiles('json/Mobills'))->filter(function ($file) {
            return str_contains($file, 'All');
        });

        $nubankFiles->each(function ($file) {
            $transaction = $this->setTransactionIds($file);

            dd($transaction);
        });



        dd($nubankFiles, $mobillsFiles);

        return 0;
    }

    private function setTransactionIds($file)
    {
        $transactions = collect(json_decode(Storage::get($file), true));
        $transactions = $transactions->map(function ($transaction) {
            $transaction['id'] = Str::uuid()->toString();
            return $transaction;
        });
        return $transactions;
    }
}
