<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Launch Values')</title>
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
<body>
    <div class="container">
        <div class="flex items-center flex-col justify-center h-full w-screen bg-[#f2a649] relative">
            <div class="absolute top-2 right-2">
                <img width="80px" src="{{ asset('img/logo2024.png') }}" alt="">
            </div>
            <img width="100px" class="mb-4" src="{{ asset("img/error.png") }}" alt="">
            <h1 class="font-medium text-2xl">Regras que ocorreram erro, em virtude de você ter informado o valor <span class="font-semibold">{{ $value }}</span> para ser inserido na coluna <span class="font-semibold">{{ $column_to_launch }}</span>:</h1>
            @foreach ($rules_error as $key => $rule)
                <p>✱ {{  Str::replaceArray('_', [$valores_percent["rules"][$key]["value"]], $dict[$rule]["text"]) }}.</p>
            @endforeach
            <img style="border-radius: 15px;" width="1000px" class="mt-4 mb-4" src="{{ asset('img/' . $filename . "_error.png") }}" alt="">
            <a href="{{ route('launch.index', ['filename' => $filename]) }}">
                <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
                    Retornar para tentar novamente...
                </button>
            </a>
        </div>
    </div>
</body>
</html>
