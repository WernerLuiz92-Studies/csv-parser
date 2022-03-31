<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ParseMobillsCsvFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:mobills-csv';

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
        $this->info('Started parsing mobills csv file');

        $files = collect(Storage::allFiles('csv/Mobills'))->filter(function ($file) {
            return str_contains($file, '.csv');
        });

        $json = collect();
        $yearly = collect();
        $monthly = collect();
        $daily = collect();

        foreach ($files as $filePath) {
            $file = Storage::get($filePath);

            $file = mb_convert_encoding($file, 'UTF-8', 'UTF-8');

            $file = str_replace("\x00", '', $file);
            $file = str_replace("\r", '', $file);

            $lines = explode("\n", $file);

            $this->info('Parsing file: ' . $filePath . ' with ' . count($lines) . ' lines');

            $headers = explode(";", str_replace('"', '', str_replace('?', '', $lines[0])));

            $lines = array_slice($lines, 1);

            $transactions = collect();

            foreach ($lines as $line) {
                $data = explode(';', str_replace('"', '', $line));

                $data = array_combine($headers, $data);

                $date = \DateTime::createFromFormat('d/m/Y', $data['Data']);
                $value = str_replace(',', '.', $data['Valor']);
                $description = $data['Descricao'];

                $transactions->push([
                    'date' => $date,
                    'value' => $value,
                    'description' => $description,
                ]);
            }

            $transactions = $transactions->each(function ($transaction) use (&$json, &$yearly, &$monthly, &$daily) {
                $day = $transaction['date']->format('d');
                $month = $transaction['date']->format('M');
                $year = $transaction['date']->format('Y');

                $transactionData = [
                    'id' => Str::uuid()->toString(),
                    'date' => $transaction['date']->format('Y-m-d'),
                    'value' => $transaction['value'],
                    'description' => $transaction['description'],
                ];

                if (!$yearly->has($year)) {
                    $yearly->put($year, collect());
                }

                if (!$monthly->has($year . '_-_' . $month)) {
                    $monthly->put($year . '_-_' . $month, collect());
                }

                if (!$daily->has($year . '_-_' . $month . '_-_' . $day)) {
                    $daily->put($year . '_-_' . $month . '_-_' . $day, collect());
                }

                $yearly->get($year)->push($transactionData);
                $monthly->get($year . '_-_' . $month)->push($transactionData);
                $daily->get($year . '_-_' . $month . '_-_' . $day)->push($transactionData);
                $json->push($transactionData);
            });
        }

        Storage::put('json/Mobills/Mobills_Transactions_-_All.json', $json->toJson());
        Storage::put('json/Mobills/Mobills_Transactions_-_Yearly.json', $yearly->toJson());
        Storage::put('json/Mobills/Mobills_Transactions_-_Monthly.json', $monthly->toJson());
        Storage::put('json/Mobills/Mobills_Transactions_-_Daily.json', $daily->toJson());

        $this->info('Finished parsing Mobills csv files');

        return Command::SUCCESS;
    }
}
