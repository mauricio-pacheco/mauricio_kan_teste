<?php

namespace Tests\Jobs;

use App\Jobs\ProcessCsvFile;
use App\Services\CsvProcessorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessCsvFileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testProcessCsvFileJob()
    {
        Queue::fake();

        $filePath = storage_path('app/csv/file.csv');

        ProcessCsvFile::dispatch($filePath);

        Queue::assertPushed(ProcessCsvFile::class, function ($job) use ($filePath) {
            return $job->filePath === $filePath;
        });
    }
}
