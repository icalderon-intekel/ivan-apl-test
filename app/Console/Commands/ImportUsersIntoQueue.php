<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ImportUser;

class ImportUsersIntoQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import-into-queue {--file=public/users.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Import users from a CSV file into the queue. " .
        "The --file option takes paths relative to the storage folder";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $csvFilePath = Storage::disk('local')->path($this->option('file'));
            $csvFileHandle = fopen($csvFilePath, 'r');

            $headers = fgetcsv($csvFileHandle);
            if ($headers !== false) {
                while (($userData = fgetcsv($csvFileHandle)) !== false) {
                    $userDataAssoc = \array_combine($headers, $userData);
                    ImportUser::dispatch($userDataAssoc);
                }
            }

            return 0;
        } catch (\Throwable $th) {
            $this->error('Unhandled exception');
            $this->info($th->getMessage());
            return 1;
        }
    }
}
