<div class="flex flex-col h-full items-center">
    <h1 class="text-xl mb-4">Ajeitando os dados</h1>
    <form action="{{ route('csv.update') }}" method="POST" 
          class="flex flex-col overflow-y-auto h-full p-4 border border-b-[#D94929] rounded-lg">
        @csrf
        <input value="{{ $name }}" name="table_name" type="text" hidden>
        @foreach ($collection as $key => $item)
            <div class="flex flex-col border-b-2 border-b-[#D94929] mb-4">
                <div class="flex justify-between items-center">
                    <label class="text-gray-700">{{ $key }}</label>
                    <input 
                        name="{{ $key . '_type' }}" 
                        type="text" 
                        value="{{ $item }}" 
                        class="border border-gray-300 rounded-lg p-2">
                </div>
                <div class="flex items-center mt-2">
                    <input type="checkbox" name="{{ $key . '_checkbox' }}">
                    <label for="default-checkbox" 
                           class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Feature de Tempo
                    </label>
                </div>
                <div class="flex items-center mt-2">
                    <input type="checkbox" name="{{ $key . '_americanformat' }}">
                    <label for="default-checkbox" 
                           class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        É formato americano ?
                    </label>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <label>Digite o separador, caso seja uma data.</label>
                    <input 
                        name="{{ $key . '_separator' }}" 
                        type="text" 
                        class="border border-gray-300 rounded-lg p-2">
                </div>
            </div>
        @endforeach
        <button type="submit" 
                class="text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
            Submeter alterações
        </button>
    </form>
</div>
