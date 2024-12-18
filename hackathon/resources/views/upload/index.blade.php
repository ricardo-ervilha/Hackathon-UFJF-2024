@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')

<form method="POST" action="{{ route('upload.upload') }}" enctype="multipart/form-data" class="flex flex-col items-center justify-center w-full max-w-md">
    @csrf
    <!-- Parágrafo de boas-vindas -->
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-black-700 dark:text-gray-300">Bem-vindo!</h1>
        <p class="text-md font-medium text-gray-700 dark:text-gray-400 mt-2">
            Este sistema foi desenvolvido para facilitar a análise de séries temporais a partir de arquivos CSV. 
            <span class="font-bold">Faça o upload do seu arquivo</span> para começar!
        </p>
    </div>
    <!-- Dropzone -->
    <div id="dropzone-container" class="flex flex-col items-center justify-center w-full">
        <label for="file_input" 
            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-200 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-all duration-300">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg id="dropzone-icon" class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
                <p id="dropzone-message" class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Clique para upload</span> ou arraste e solte
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Somente arquivos CSV (MAX. 5MB)</p>
            </div>
            <input id="file_input" type="file" name="file_input" class="hidden" />
        </label>
    </div>
    <!-- Botão de envio -->
    <button class="bg-[#f27830] font-medium p-3 mt-4 rounded-lg hover:bg-[#d94929]" type="submit">
        Import Dataset
    </button>
</form>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('file_input');
        const dropzoneContainer = document.getElementById('dropzone-container');
        const dropzoneText = document.getElementById('dropzone-text');
        const dropzoneIcon = document.getElementById('dropzone-icon');
        const dropzoneMessage = document.getElementById('dropzone-message');

        fileInput.addEventListener('change', (event) => {
            if (fileInput.files.length > 0) {
                // Change styles when a file is selected
                dropzoneContainer.classList.add('bg-green-100', 'dark:bg-green-900');
                dropzoneContainer.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                dropzoneText.textContent = 'File ready for upload!';
                dropzoneIcon.classList.add('text-green-500');
                dropzoneIcon.classList.remove('text-gray-500');
                dropzoneMessage.textContent = `Selected file: ${fileInput.files[0].name}`;
            } else {
                // Revert styles if no file is selected
                dropzoneContainer.classList.remove('bg-green-100', 'dark:bg-green-900');
                dropzoneContainer.classList.add('bg-gray-50', 'dark:bg-gray-700');
                dropzoneText.textContent = 'Drag your CSV file here';
                dropzoneIcon.classList.remove('text-green-500');
                dropzoneIcon.classList.add('text-gray-500');
                dropzoneMessage.textContent = 'Click to upload or drag and drop';
            }
        });
    });
</script>

@endsection
