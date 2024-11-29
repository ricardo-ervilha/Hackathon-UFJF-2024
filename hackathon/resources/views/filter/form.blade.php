<div class="flex flex-col h-full w-full items-center">
    <h1 class="text-xl mb-4 mt-4 font-medium">Filtro na série <span class="font-bold">"{{ $filename }}"</span></h1>
    <form action="{{ route('filter.filter') }}" method="GET" 
          class="flex flex-col overflow-y-auto p-4 border border-[#D94929] rounded-lg w-1/2">
        @csrf
        <input name="filename" type="text" hidden value="{{ $filename }}">
        <label for="filter_string" class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">Digite aqui o filtro que você deseja aplicar.</label>
        <textarea id="message" name="filter_string" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-4" placeholder="Digite aqui..."></textarea>
        <button type="submit" 
                class="text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
            Aplicar filtro...
        </button>
    </form>
</div>