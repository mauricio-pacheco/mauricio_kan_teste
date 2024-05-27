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

            // Despachar o job para a fila 'high'
            ProcessCsvFile::dispatch($path)->onQueue('high');

            return response()->json(['message' => 'Arquivo CSV enviado para processamento.'], 200);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }
}
