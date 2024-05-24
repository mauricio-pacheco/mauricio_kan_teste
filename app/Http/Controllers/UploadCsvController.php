<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessCsvFile;

class UploadCsvController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Verifique se é um arquivo CSV
            if ($file->getClientOriginalExtension() !== 'csv') {
                return response()->json(['error' => 'O arquivo enviado não é um arquivo CSV.'], 400);
            }

            // Salve o arquivo em storage/csv (crie o diretório se não existir)
            $path = $file->storeAs('csv', $file->getClientOriginalName());

            // Despache um trabalho para processar o arquivo CSV
            ProcessCsvFile::dispatch($path)->onQueue('high'); // Coloque o trabalho em uma fila de alta prioridade

            // Retorna uma resposta indicando que o arquivo foi recebido para processamento
            return response()->json(['message' => 'Arquivo CSV enviado para processamento.'], 200);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }
}

