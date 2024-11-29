@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<h1></h1>
<div class="flex flex-col">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-black-700 dark:text-gray-300">Gráfico da Série Temporal</h1>
        <p class="text-md font-medium text-gray-700 dark:text-gray-400 mt-2">
            {{ $file_name }}
        </p>
    </div>
    <img width="1328px" style="border-radius: 15px;" src="{{ asset('img/' . $path) }}" alt="">
    <div class="flex flex-col">
        <div class="w-full flex justify-end mb-4">
            <a href="{{ route('graph.download', request('file_name')) }}" class="mt-6">
                <button class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                    Fazer download...
                </button>
            </a>
        </div>
        <div class="w-full flex justify-end mb-4">
            <a href="{{ route('validation.index', request('file_name')) }}" class="mt-6">
                <button class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                    Seguir para Regras de Validação...
                </button>
            </a>
        </div>
    </div>
</div>

@endsection