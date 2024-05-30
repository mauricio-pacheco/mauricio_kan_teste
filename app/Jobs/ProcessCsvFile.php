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
use Illuminate\Support\Facades\DB;

class ProcessCsvFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $offset;
    protected $chunkSize;

    public function __construct($filePath, $offset = 0, $chunkSize = 1000)
    {
        $this->filePath = $filePath;
        $this->offset = $offset;
        $this->chunkSize = $chunkSize;
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
            $records = $reader->getRecords(); // Obter todos os registros do CSV

            DB::beginTransaction(); // Iniciar transação

            $processedRecords = 0;

            foreach ($records as $index => $record) {
                // Processar apenas registros dentro do intervalo de offset e chunkSize
                if ($index < $this->offset) {
                    continue;
                }
                if ($index >= $this->offset + $this->chunkSize) {
                    break;
                }

                logger('Processando registro de índice: ' . $index);

                if (!isset($record['name'], $record['governmentId'], $record['email'], $record['debtAmount'], $record['debtDueDate'], $record['debtId'])) {
                    logger('Ignorando registro devido a chaves ausentes: ' . json_encode($record));
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
                logger('Gerando boleto para: ' . $name);
                $boleto = new Boleto();
                $boleto->name = $name;
                $boleto->government_id = $governmentId;
                $boleto->email = $email;
                $boleto->debt_amount = $debtAmount;
                $boleto->due_date = $debtDueDate;
                $boleto->debt_id = $debtID;
                $boleto->save();

                $processedRecords++;
            }

            DB::commit(); // Commit da transação

            // Processando ochunk completo, despachar o próximo job
            if ($processedRecords === $this->chunkSize) {
                ProcessCsvFile::dispatch($this->filePath, $this->offset + $this->chunkSize, $this->chunkSize);
            }

            // Enviar e-mail após processar o chunk
            //Mail::to('mauricio@casadaweb.net')->send(new BoletoGenerated($processedRecords, $this->offset, $this->chunkSize));

            logger('Chunk processado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback da transação em caso de erro
            logger('Erro no job: ' . $e->getMessage());
        }
    }
}
