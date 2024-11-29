<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Display Filter Informations')</title>
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
            <div class="mb-6 text-center">
                <h1 class="text-2xl mt-4 font-bold text-black-700 dark:text-gray-300">Resultado do Filtro</h1>
                <p class="text-md font-medium text-gray-700 dark:text-gray-400 mt-2">
                    @foreach ($result as $item)
                    <ul>
                        @foreach ($item as $key => $value)
                            <li>{{ $key }}: {{ $value }}</li>
                        @endforeach
                    </ul>
                    <hr>
                @endforeach
                </p>
                <a href="{{ route('filter.index', ['filename'=> $filename]) }}">
                    <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
                        Retornar para testar outro filtro..
                    </button>
                </a>
            </div>
            
            
        </div>
    </div>
</body>
</html>

