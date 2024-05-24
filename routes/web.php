<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadCsvController;

use Illuminate\Support\Facades\Storage;

Route::get('/csrf-token', function() {
    return response()->json(['csrfToken' => csrf_token()]);
});


Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/upload', [UploadCsvController::class, 'upload']);

Route::get('/api/files', function () {
    $files = Storage::files('csv'); // Obtém a lista de arquivos no diretório storage/app/csv
    return response()->json(['files' => $files]);
});