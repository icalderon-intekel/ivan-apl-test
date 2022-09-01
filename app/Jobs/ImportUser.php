<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $userData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userData)
    {
        $this->queue = 'users-insert';
        $this->userData = $userData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            User::create($this->userData);
        } catch (\Throwable $th) {
            Log::error('ImportUser Job Exception: ' . $th->__toString());
            $this->fail($th);
        }
    }
}
