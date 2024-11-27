@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<form action="{{ route('table.display') }}" method="GET">
    <div>
        <label for="name_table" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome da Tabela</label>
        <select name="name_table" id="name_table">
            @foreach($names as $name)
                @if($name != 'csv_files')
                    <option value="{{ $name }}">{{ $name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>

@endsection