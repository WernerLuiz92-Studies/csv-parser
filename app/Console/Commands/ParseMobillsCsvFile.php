<?php

namespace App\Console\Commands;

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

        $files = Storage::allFiles('csv/Mobills');

        foreach ($files as $filePath) {
            $file = Storage::get($filePath);

            $file = mb_convert_encoding($file, 'UTF-8', 'UTF-8');

            $file = str_replace("\x00", '', $file);
            $file = str_replace("\r", '', $file);

            $lines = explode("\n", $file);

            $this->info('Parsing file: ' . $filePath . ' with ' . count($lines) . ' lines');

            $headers = explode(";", str_replace('"', '', str_replace('?', '', $lines[0])));

            $lines = array_slice($lines, 1);

            $json = [];

            foreach ($lines as $line) {
                $data = explode(';', str_replace('"', '', $line));

                $data = array_combine($headers, $data);

                $json[] = $data;
            }

            Storage::put('json/Mobills/' . basename($filePath, '.csv') . '.json', json_encode($json));
        }

        $this->info('Finished parsing mobills csv file');

        return Command::SUCCESS;
    }
}