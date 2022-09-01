<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a CSV file with randomized user records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $numberOfRecords = 100000;
        $bar = $this->output->createProgressBar($numberOfRecords);
        $csvHeader = [
            'name',
            'lastname',
            'phone_number',
            'email',
            'password',
            'deleted_at',
        ];

        $this->line('Writing users.csv to storage/app/public:');
        $this->newLine();

        try {
            $csvFilePath = Storage::disk('local')->path('public/users.csv');
            $csvFileHandle = fopen($csvFilePath, 'w');

            \fputcsv($csvFileHandle, $csvHeader) ;

            for ($currentRecord = 0; $currentRecord < $numberOfRecords; $currentRecord++) {
                \fputcsv($csvFileHandle, $this->generateRecord());
                $bar->advance();
            }

            fclose($csvFileHandle);

            $bar->finish();
            $this->newLine(2);
            $this->line('Done.');
        } catch (\Throwable $th) {
            $this->error('Unhandled exception');
            $this->info($th->getMessage());
        }
    }

    private function generateRecord(): array
    {
        $record = [
            fake()->firstName(), // name
            fake()->lastName, // lastname
            fake()->phoneNumber, // phone_number
            fake()->unique()->companyEmail, // email
            fake()->shuffle('Laravel_SEP.2022'), // password
            (fake()->randomDigit > 7) ? '1970-01-01 00:00:00' : 'null' // deleted_at
        ];

        return $record;
    }
}
