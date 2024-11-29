@extends('layouts.app')

@section('title', 'Edit Your Dataset Metamodel')

@section('content')
    
@include('edit_csv.form', ['collection' => $data])

@endsection