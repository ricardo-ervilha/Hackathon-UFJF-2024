<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Default Title')</title>
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
        <div class="flex items-center flex-col h-full w-screen bg-[#f2a649] relative">
            <div class="absolute top-2 right-2">
                <img width="80px" src="{{ asset('img/logo2024.png') }}" alt="">
            </div>
            <h1 class="font-medium text-2xl mt-3 mb-3">Regras de Validação</h1>
            <form action="{{ route('validation.validation') }}" method="post">
                @csrf
                <input name="file_name" type="text" hidden value="{{ Route::current()->parameter('file_name') }}">
                @foreach($columns as $col)
                    <div class="border border-[#D94929] rounded-lg mt-2 mb-2">
                        <h1 class="font-bold text-lg ml-3 mt-2"> Campo "{{ $col }}"</h1>
                        <x-rules :rules=$rules :col=$col></x-rules>
                    </div>
                @endforeach
                <div class="w-full flex justify-center mb-4">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                        Submeter regras e seguir para lançamentos...
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

