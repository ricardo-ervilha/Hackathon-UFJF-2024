@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')

<img width="100px" class="mb-4" src="{{ asset("img/error.png") }}" alt="">
<h1 class="font-medium text-2xl">Regras que ocorreram erro, em virtude de você ter informado o valor <span class="font-semibold">{{ $value }}</span> para ser inserido na coluna <span class="font-semibold">{{ $column_to_launch }}</span>:</h1>
@foreach ($rules_error as $rule)
    <p>✱ {{  Str::replaceArray('_', [$valores_percent["rules"][$rule]["value"]], $dict[$rule]["text"]) }}.</p>
@endforeach
<img style="border-radius: 15px;" width="1000px" class="mt-4 mb-4" src="{{ asset('img/' . $filename . "_error.png") }}" alt="">
<a href="{{ route('launch.index', ['filename' => $filename]) }}">
    <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
        Retornar para tentar novamente...
    </button>
</a>
@endsection