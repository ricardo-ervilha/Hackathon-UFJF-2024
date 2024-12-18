<div class="flex flex-col h-full items-center">
    <h1 class="text-xl mb-4 mt-4 font-semibold">Regras de Produção</h1>
    <form action="{{ route('csv.update') }}" method="POST" 
          class="flex flex-col overflow-y-auto p-4 border border-[#D94929] rounded-lg">
        @csrf
        <input name="" type="text" hidden>
        
            <div class="flex flex-col border-b-2 border-b-[#D94929] mb-4 p-4">
                <div class="flex justify-between items-center">
                    O lançamento não pode ser 
                    <span><input name="" type="text" value="" class="border border-gray-300 rounded-lg p-2"></span>
                    superior em relação à curva de .
                </div>
            </div>
            <div class="flex flex-col border-b-2 border-b-[#D94929] mb-4 p-4">
                <div class="flex justify-between items-center">
                    O lançamento não pode ser 
                    <span><input name="" type="text" value="" class="border border-gray-300 rounded-lg p-2"></span>
                    superior em relação à média dos lançamentos anteriores.
                </div>
            </div>
            <div class="flex flex-col border-b-2 border-b-[#D94929] mb-4 p-4">
                <div class="flex justify-between items-center">
                    O lançamento não pode ser 
                    <span><input name="" type="text" value="" class="border border-gray-300 rounded-lg p-2"></span>
                    superior em relação ao lançamento anterior.
                </div>
            </div>
        
        <button type="submit" 
                class="text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
            Submeter produções
        </button>
    </form>
</div>
