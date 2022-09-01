<?php

namespace Tests\Feature;

use App\Jobs\ImportUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportUsersTest extends TestCase
{
    /**
     * @depends testTheCreateCsvCommandProducesACsvFile
     */
    public function testJobsArePushedIntoQueue()
    {
        Queue::fake();
        Queue::assertNothingPushed();

        $this->artisan('users:import-into-queue')->assertExitCode(0);

        Queue::assertPushed(ImportUser::class, 100);
    }

    /**
     * @depends testJobsArePushedIntoQueue
     */
    public function testQueueIsProcessed()
    {
        $this->artisan('users:process-queue')->assertExitCode(0);
    }

    /**
     * Test csv command.
     *
     * @return void
     */
    public function testTheCreateCsvCommandProducesACsvFile()
    {
        // Create test csv
        Storage::disk('local')->delete('public/users.csv'); // Delete previous
        $this->artisan('csv:create --records=100')->assertExitCode(0);

        // Chech if file exists in storage
        $exists = Storage::disk('local')->exists('public/users.csv');
        $this->assertTrue($exists);
    }
}
