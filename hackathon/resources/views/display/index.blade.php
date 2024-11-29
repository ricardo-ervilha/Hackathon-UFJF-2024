@extends('layouts.app')

@section('title', 'Select Your Dataset')

@section('content')
    
<form action="{{ route('table.display') }}" method="GET">
    <div class="mb-6 text-center">
        <p class="text-md font-medium text-gray-700 dark:text-gray-400 mt-2">
            Nessa parte, você deverá escolher um dos datasets registrados em nosso banco de dados.
        </p>
    </div>
    <div id="dropzone-container" class="flex flex-col items-center justify-center w-full">
        <div class="mb-1">
            <label for="name_table" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome do Dataset</label>
            <select name="name_table" id="name_table" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach($names as $name)
                    @if($name != 'csv_files')
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
            Escolher
        </button>
    </div>
</form>

@endsection