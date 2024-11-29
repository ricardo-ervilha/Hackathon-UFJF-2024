@extends('layouts.app')

@section('title', 'Launch Sucess')

@section('content')
     
<img width="100px" class="mb-4" src="{{ asset("img/sucess.png") }}" alt="">
<h1 class="font-medium text-2xl">Valor <span class="font-semibold">{{ $value }}</span> inserido na coluna <span class="font-semibold">{{ $column_to_launch }}.</span></h1>
<a href="{{ route('launch.index', ['filename' => $filename]) }}">
    <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
        Retornar para inserir novos...
    </button>
</a>
<a href="{{ route('filter.index', ['filename'=> $filename]) }}">
    <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
        Avançar para parte de filtros..
    </button>
</a>
@endsection