<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ParseNuBankCsvFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:nubank-csv';

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

        $files = Storage::allFiles('csv/NuBank');

        foreach ($files as $filePath) {
            $file = Storage::get($filePath);

            $file = str_replace("\x00", '', $file);
            $file = str_replace("\r", '', $file);
            $file = trim($file);

            $lines = explode("\n", $file);

            $this->info('Parsing file: ' . $filePath . ' with ' . count($lines) . ' lines');

            $headers = explode(",", str_replace('"', '', str_replace('?', '', $lines[0])));

            $lines = array_slice($lines, 1);

            $json = [];

            foreach ($lines as $line) {
                $data = explode(',', str_replace('"', '', $line));

                $data = array_combine($headers, $data);

                $json[] = $data;
            }

            Storage::put('json/NuBank/' . basename($filePath, '.csv') . '.json', json_encode($json));
        }

        $this->info('Finished parsing Nu Bank csv file');

        return Command::SUCCESS;
    }
}