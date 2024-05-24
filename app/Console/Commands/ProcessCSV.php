<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use League\Csv\Reader;

class ProcessCSV extends Command
{
    protected $signature = 'csv:process {file}';

    protected $description = 'Processa o arquivo CSV';

    public function handle()
    {
        $filePath = $this->argument('file');

        $csv = Reader::createFromPath(storage_path('app/'.$filePath), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $this->processRecord($record);
        }

        $this->info('Processamento do arquivo CSV concluído.');
    }

    private function processRecord($record)
    {
        // Lógica para processar cada linha do arquivo CSV
        // Implemente a geração de boletos e o envio de e-mails aqui
    }
}
