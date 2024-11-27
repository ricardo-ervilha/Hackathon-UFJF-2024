@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<h2>Ajeitando os dados</h1>
@include('edit_csv.form', ['collection' => $data])

@endsection