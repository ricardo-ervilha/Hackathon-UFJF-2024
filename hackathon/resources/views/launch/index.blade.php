@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<form action="{{ route('launch.launch') }}" method="POST">
    @csrf
    <div class="mb-6 text-center">
        <p class="text-md font-medium text-gray-700 dark:text-gray-400 mt-2">
            Escolha uma <span class="font-bold">coluna</span> e o respectivo <span class="font-bold">valor</span> para ser lançado.
        </p>
    </div>
    <div id="dropzone-container" class="flex flex-col items-center justify-center w-full">
            @csrf
            <input name="filename" type="text" hidden value="{{ $filename }}">
            <input name="time_column" type="text" hidden value="{{ $time_column }}">
            <div class="w-full flex flex-col justify-center">
                <div class="mb-2 mt-2">
                    <label for="time_column_value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor de "{{ $time_column }}"</label>
                    <input name="time_column_value" type="text" id="time_column_value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required />
                </div>
                <div class="mb-2 mt-2">
                    <label for="column" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecione um campo</label>
                    <select id="column" name="column" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      @foreach($columns as $column)
                            <option value="{{ $column }}">{{ $column }}</option>
                      @endforeach
                    </select>
                </div>
                <div class="mb-2 mt-2">
                    <label for="value_column" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Digite o valor a ser lançado</label>
                    <input type="number" name="value_column" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required />
                </div>
            </div>
            <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
                Lançar valor...
            </button>
    </div>
</form>

@endsection