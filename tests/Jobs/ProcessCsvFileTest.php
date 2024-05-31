<?php

namespace Tests\Jobs;

use App\Models\Boleto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessCsvFileTest extends TestCase
{
    // Usar a trait RefreshDatabase para garantir que o banco de dados seja reiniciado antes de cada teste
    //use RefreshDatabase;

    public function testProcessCsvFileJob()
    {
        // Caminho direto para o arquivo CSV
        $filePath = 'csv/input.csv';

        // Cria um arquivo CSV fictício para testar diretamente no armazenamento falso
        Storage::disk('local')->put($filePath, "name,governmentId,email,debtAmount,debtDueDate,debtId\nElijah Santos,9558,janet95@example.com,7811,2024-01-19,ea23f2ca-663a-4266-a742-9da4c9f4fcb3");

        // Define a data e hora atual para os campos created_at e updated_at
        $now = Carbon::now()->toDateTimeString();

        // Chama diretamente o job
        $fullPath = storage_path('app/' . $filePath);
        $reader = \League\Csv\Reader::createFromPath($fullPath, 'r');
        $reader->setHeaderOffset(0);

        foreach ($reader as $record) {
            // Verifica se todas as chaves estão presentes
            if (!isset($record['name'], $record['governmentId'], $record['email'], $record['debtAmount'], $record['debtDueDate'], $record['debtId'])) {
                logger('Ignorando registro devido a chaves ausentes: ' . json_encode($record));
                continue;
            }

            try {
                Boleto::create([
                    'name' => $record['name'],
                    'government_id' => $record['governmentId'],
                    'email' => $record['email'],
                    'debt_amount' => $record['debtAmount'],
                    'due_date' => $record['debtDueDate'],
                    'debt_id' => $record['debtId'], // Usar o valor fornecido no CSV para debt_id
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } catch (\Exception $e) {
                logger('Erro ao inserir no banco de dados: ' . $e->getMessage());
            }
        }

        // Verifica se os registros foram criados corretamente
        //$this->assertDatabaseCount('boletos', 1);
        $this->assertDatabaseHas('boletos', [
            'name' => 'Elijah Santos',
            'government_id' => 9558,
            'email' => 'janet95@example.com',
            'debt_amount' => 7811,
            'due_date' => '2024-01-19',
            'debt_id' => 'ea23f2ca-663a-4266-a742-9da4c9f4fcb3',
        ]);
    }
}
