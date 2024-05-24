<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

            // Obtém a lista de arquivos atualizada
            $files = Storage::files('csv');

            // Retorna a lista de arquivos atualizada
            return response()->json(['message' => 'Arquivo CSV enviado com sucesso.', 'path' => $path, 'files' => $files], 200);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }
}
