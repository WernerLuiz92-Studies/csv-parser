<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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
        $nubankFiles = Storage::allFiles('json/NuBank');
        $mobillsFiles = Storage::allFiles('json/Mobills');

        $nubankTransactions = collect();
        $mobillsTransactions = collect();

        foreach ($nubankFiles as $file) {
            $fileTransactions = collect(json_decode(Storage::get($file), true));

            foreach ($fileTransactions as $transaction) {
                $date = Carbon::createFromFormat('d/m/Y', $transaction['Data']);

                $transaction['timestamp'] = $date->getTimestamp();

                $nubankTransactions->push($transaction);
            }
        }

        foreach ($mobillsFiles as $file) {
            $fileTransactions = collect(json_decode(Storage::get($file), true));

            foreach ($fileTransactions as $transaction) {
                $date = Carbon::createFromFormat('d/m/Y', $transaction['Data']);

                $transaction['timestamp'] = $date->getTimestamp();

                $mobillsTransactions->push($transaction);
            }
        }

        $nubankTransactions = $nubankTransactions->sortBy('timestamp');
        $mobillsTransactions = $mobillsTransactions->sortBy('timestamp');

        $nubankTransactions->each(function ($transaction) use ($mobillsTransactions) {
            $mobillsTransactions->each(function ($mobillsTransaction) use ($transaction) {
                if ($transaction['timestamp'] == $mobillsTransaction['timestamp']) {
                    if ($transaction['Valor'] === $mobillsTransaction['Valor']) {
                        $this->info('Nubank: ' . $transaction['Descricao']);
                        $this->info('Mobills: ' . $mobillsTransaction['Descricao']);
                        $this->info($transaction['timestamp']);
                        $this->info($transaction['Valor']);
                        $this->info($mobillsTransaction['Valor']);
                        $this->info('------------------------------------');
                        $this->output->newLine(3);

                        $transaction->delete();
                        $mobillsTransaction->delete();
                    }
                }
            });
        });

        dd($nubankTransactions, $mobillsTransactions);

        return 0;
    }
}