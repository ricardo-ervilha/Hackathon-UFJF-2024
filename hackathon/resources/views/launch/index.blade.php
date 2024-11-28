@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
    
<form action="{{ route('table.display') }}" method="GET">
    <div class="mb-6 text-center">
        <p class="text-md font-medium text-gray-700 dark:text-gray-400 mt-2">
            Escolha uma <span class="font-bold">coluna</span> e o respectivo <span class="font-bold">valor</span> para ser lançado.
        </p>
    </div>
    <div id="dropzone-container" class="flex flex-col items-center justify-center w-full">
        <div class="w-full">
            <div class="max-w-sm mx-auto mb-4">
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecione uma coluna</label>
                <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option selected>Choose a country</option>
                  <option value="US">United States</option>
                  <option value="CA">Canada</option>
                  <option value="FR">France</option>
                  <option value="DE">Germany</option>
                </select>
            </div>
            <div class="mb-1">
                <label for="name_table" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Digite o valor</label>
                <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required />
            </div>
        </div>
        <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
            Lançar valor
        </button>
    </div>
</form>

@endsection