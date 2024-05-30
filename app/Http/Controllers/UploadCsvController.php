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

            if ($file->getClientOriginalExtension() !== 'csv') {
                return response()->json(['error' => 'O arquivo enviado não é um arquivo CSV.'], 400);
            }

            $path = $file->storeAs('csv', $file->getClientOriginalName());

            // Configura a conexão da fila para utilizar a fila sync
            config(['queue.default' => 'sync']);

            $filePath = 'csv/input.csv';

            // Despachar o job para a fila 'high'
            ProcessCsvFile::dispatch($filePath)->onQueue('high')->delay(now()->addSeconds(3000));

            return response()->json(['message' => 'Arquivo CSV enviado para processamento.'], 200);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }
}
