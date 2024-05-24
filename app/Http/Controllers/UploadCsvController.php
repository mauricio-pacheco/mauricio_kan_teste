<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

            // Faça o que quiser com o arquivo, como processá-lo ou salvá-lo no banco de dados
            // Exemplo: processamento de arquivo CSV
            // $data = array_map('str_getcsv', file(storage_path('app/' . $path)));

            return response()->json(['message' => 'Arquivo CSV enviado com sucesso.', 'path' => $path], 200);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }
}
