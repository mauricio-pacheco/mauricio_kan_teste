<?php

namespace App\Services;

use League\Csv\Reader;
use App\Models\Boleto;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletoGenerated;

class CsvProcessorService
{
    public function process($filePath)
    {
        try {
            // Carregar o arquivo CSV usando League\Csv\Reader
            $reader = Reader::createFromPath(storage_path('app/' . $filePath), 'r');
            $reader->setHeaderOffset(0);

            // Processar o arquivo em chunks
            $chunkSize = 1000; // Tamanho do chunk
            $records = $reader->getRecords(); // Obter todos os registros do CSV

            foreach ($records as $index => $record) {
                // Processar cada registro
                $name = $record['name'];
                $governmentId = $record['governmentId'];
                $email = $record['email'];
                $debtAmount = $record['debtAmount'];
                $debtDueDate = $record['debtDueDate'];
                $debtID = $record['debtID'];

                // Lógica para gerar boleto
                $boleto = new Boleto();
                $boleto->name = $name;
                $boleto->government_id = $governmentId;
                $boleto->email = $email;
                $boleto->debt_amount = $debtAmount;
                $boleto->due_date = $debtDueDate;
                $boleto->debt_id = $debtID;
                $boleto->save();

                // Envio do boleto por e-mail
                Mail::to($email)->send(new BoletoGenerated($boleto));

                // Lógica para liberar memória após processar um chunk
                if (($index + 1) % $chunkSize === 0) {
                    // Limpar a memória para evitar vazamento de memória
                    unset($records);
                    $records = [];
                }
            }

            return 200; // Status code de sucesso
        } catch (\Exception $e) {
            // Tratando exceções e retornar um status code de erro apropriado
            return 500; // Status code de erro
        }
    }
}
