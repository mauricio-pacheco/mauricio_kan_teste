<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use App\Models\Boleto;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletoGenerated;

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
        try {
            // Usar o caminho completo diretamente
            $fullPath = storage_path('app/' . $this->filePath);
      
            if (!file_exists($fullPath)) {
                return;
            }

            logger('Carregando Arquivo CSV');
            $reader = Reader::createFromPath($fullPath, 'r');
            $reader->setHeaderOffset(0);

            // Processar o arquivo em chunks
            $chunkSize = 1000; // Tamanho do chunk
            $records = $reader->getRecords(); // Obter todos os registros do CSV

            foreach ($records as $index => $record) {
                logger('Processing record index: ' . $index);

                if (!isset($record['name'], $record['governmentId'], $record['email'], $record['debtAmount'], $record['debtDueDate'], $record['debtId'])) {
                    logger('Skipping record due to missing keys: ' . json_encode($record));
                    continue;
                }

                // Processar cada registro
                $name = $record['name'];
                $governmentId = $record['governmentId'];
                $email = $record['email'];
                $debtAmount = $record['debtAmount'];
                $debtDueDate = $record['debtDueDate'];
                $debtID = $record['debtId'];

                // Lógica para gerar boleto
                logger('Generating boleto for: ' . $name);
                $boleto = new Boleto();
                $boleto->name = $name;
                $boleto->government_id = $governmentId;
                $boleto->email = $email;
                $boleto->debt_amount = $debtAmount;
                $boleto->due_date = $debtDueDate;
                $boleto->debt_id = $debtID;
                $boleto->save();

                // Envio do boleto por e-mail
                //logger('Sending email to: ' . $email);
                //Mail::to($email)->send(new BoletoGenerated($boleto));

                // Lógica para liberar memória após processar um chunk
                if (($index + 1) % $chunkSize === 0) {
                    logger('Clearing memory at chunk: ' . ($index + 1));
                    // Limpar a memória para evitar vazamento de memória
                    unset($records);
                    $records = [];
                }
            }

            // Processar o último chunk (se houver)
            if (!empty($records)) {
                logger('Processing final chunk');
                // Processar o chunk final
                foreach ($records as $record) {
                    // Verificar se todas as chaves esperadas existem no registro
                    if (!isset($record['name'], $record['governmentId'], $record['email'], $record['debtAmount'], $record['debtDueDate'], $record['debtId'])) {
                        logger('Skipping record due to missing keys: ' . json_encode($record));
                        continue; // Pular registro se alguma chave estiver faltando
                    }

                    // Processar cada registro do último chunk
                    $name = $record['name'];
                    $governmentId = $record['governmentId'];
                    $email = $record['email'];
                    $debtAmount = $record['debtAmount'];
                    $debtDueDate = $record['debtDueDate'];
                    $debtID = $record['debtId'];

                    // Lógica para gerar boleto
                    logger('Generating boleto for: ' . $name);
                    $boleto = new Boleto();
                    $boleto->name = $name;
                    $boleto->government_id = $governmentId;
                    $boleto->email = $email;
                    $boleto->debt_amount = $debtAmount;
                    $boleto->due_date = $debtDueDate;
                    $boleto->debt_id = $debtID;
                    $boleto->save();

                    // Envio do boleto por e-mail
                    //Mail::to($email)->send(new BoletoGenerated($boleto));
                }
            }

            // Após processar todos os registros, pode ser executado qualquer lógica adicional necessária
            logger('Job Completo!');
        } catch (\Exception $e) {
            logger('Erro no job: ' . $e->getMessage());
        }
    }

    public function getFilePath()
    {
        return $this->filePath;
    }
}
