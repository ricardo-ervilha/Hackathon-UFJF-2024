@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
@include('edit_csv.form', ['collection' => $data])

@endsection