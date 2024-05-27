<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use App\Models\Boleto; // Supondo que você tenha um modelo Boleto para armazenar informações sobre os boletos gerados
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletoGenerated; // Supondo que você tenha um Mailable para enviar boletos por e-mail

class ProcessCsvFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        // Carregar o arquivo CSV usando League\Csv\Reader
        $reader = Reader::createFromPath(storage_path('app/' . $this->filePath), 'r');
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

        // Processar o último chunk (se houver)
        if (!empty($records)) {
            // Processar o chunk final
            foreach ($records as $record) {
                // Processar cada registro do último chunk
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
            }
        }

        // Após processar todos os registros, pode ser executado qualquer lógica adicional necessária
        // Por exemplo, enviar um e-mail de relatório ou atualizar o status do processamento do arquivo CSV
    
    }
}
