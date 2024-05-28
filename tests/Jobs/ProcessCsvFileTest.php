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
        // Configura a conexão da fila para utilizar a fila sync (ou a que você está usando)
        config(['queue.default' => 'sync']);

        $filePath = 'csv/input.csv';

        // Cria um arquivo CSV fictício para testar
        Storage::fake('local');
        $csvData = "name,governmentId,email,debtAmount,debtDueDate,debtID\n";
        $csvData .= "John Doe,123456789,johndoe@example.com,1000.00,2023-12-31,1\n";
        Storage::disk('local')->put($filePath, $csvData);

        // Despacha o job
        ProcessCsvFile::dispatch($filePath);

        // Verifica se o boleto foi criado
        $this->assertDatabaseHas('boletos', [
            'name' => 'John Doe',
            'government_id' => '123456789',
            'email' => 'johndoe@example.com',
            'debt_amount' => 1000.00,
            'due_date' => '2023-12-31',
            'debt_id' => 1,
        ]);

        // Verifica se o e-mail foi enviado
        Mail::assertSent(BoletoGenerated::class, function ($mail) {
            return $mail->hasTo('johndoe@example.com');
        });
    }
}
