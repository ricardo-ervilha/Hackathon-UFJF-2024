<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'CSV Display')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        .container{
            font-family: "Montserrat", sans-serif;
        }
    </style>
</head>
<body class="bg-[#f2a649]">
    <div class="container mx-auto w-full">
        <div class="flex flex-col items-center w-full justify-center min-h-screen bg-[#f2a649]">
            <h1 class="text-lg font-medium mb-4 mt-4">CSV: <span class="font-bold">{{ request('name_table') }}</span></h1>
            <p class="text-lg font-medium mb-4 mt-4">Impressão do CSV após remover linhas com valores <span class="font-bold">nan</span>.</p>
            <div class="w-full max-w-6xl bg-white shadow-md rounded-lg p-6">
                <x-table :columns="$columns" :values="$values"></x-table>
            </div>
            <div class="w-full flex justify-end mb-4">
                <a href="{{ route('csv.edit', request('name_table')) }}" class="mt-6">
                    <button class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                        Seguir para definição do Metamodelo...
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>