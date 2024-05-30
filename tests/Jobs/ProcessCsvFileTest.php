<?php

namespace Tests\Jobs;

use App\Jobs\ProcessCsvFile;
use App\Models\Boleto;
use App\Mail\BoletoGenerated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessCsvFileTest extends TestCase
{
    use RefreshDatabase;

    public function testProcessCsvFileJob()
    {
        // Configura a conexão da fila para utilizar a fila sync
        config(['queue.default' => 'sync']);

        $filePath = 'csv/input.csv';

        // Despacha o job
        ProcessCsvFile::dispatch($filePath);

        // Verifica se o e-mail foi enviado
        /*
        Mail::assertSent(BoletoGenerated::class, function ($mail) {
            return $mail->hasTo('mauricio@casadaweb.net');
        });
        */
    }
}
