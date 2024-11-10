<?php

namespace App\Console\Commands;

use App\Models\Groceries;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportGroceries extends Command
{
    protected $signature = 'import:groceries';
    protected $description = 'Import groceries data from a CSV file';

    public function handle(): void
    {
        $filePath = public_path('data/groceries.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return;
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle, 1000, ';');

            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $data = [
                    'item_name' => $row[0] ?? null,
                    'suger_per_100' => $row[1] ?? null,
                ];

                Groceries::create([
                    'name' => $data['item_name'],
                    'sugar_per_100' => $data['suger_per_100'] ?? 10,
                ]);
            }

            fclose($handle);
            $this->info("Data import complete.");
        } else {
            $this->error("Unable to open file: {$filePath}");
        }
    }
}
