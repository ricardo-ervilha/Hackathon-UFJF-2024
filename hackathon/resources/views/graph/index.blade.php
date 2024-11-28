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

</div>

@endsection