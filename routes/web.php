<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CsvUploadController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/upload', [CsvUploadController::class, 'upload']);