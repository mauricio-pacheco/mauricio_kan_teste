<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload do CSV</title>

    <!-- Links para os arquivos CSS do Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Adicione o link para o seu script JS compilado -->
    @vite(['resources/js/app.jsx'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
