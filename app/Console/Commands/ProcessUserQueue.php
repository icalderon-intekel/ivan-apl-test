<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessUserQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:process-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the user data in the queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $commandResult = Artisan::call('queue:work redis --queue=users-insert --stop-when-empty');

        return $commandResult;
    }
}
