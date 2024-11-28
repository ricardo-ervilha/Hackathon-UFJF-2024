@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<h1>Imagem do gráfico...</h1>
<p>{{ $path }}</p>
<img src="{{ asset("img/" . $path) }}" alt="">

@endsection