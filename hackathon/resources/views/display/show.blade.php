@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<h1>Printando as tabelas...</h1>
<x-table :columns=$columns :values=$values></x-table>

<a href="{{ route('csv.edit', request('name_table')) }}">
    <button class="bg-red-500">Seguir para definição dos dados...</button>
</a>

@endsection