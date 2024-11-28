<div class="p-4 space-y-4">
    @foreach ($rules as $rule)
        {{-- {{ $rule }} --}}
        <div class="flex items-center space-x-4 border-b pb-4">
            <!-- Texto da regra com placeholder para o input -->
            <p class="text-gray-700">
                {{  $rule['text'] }}
            </p>
            <!-- Input para o valor associado a _ -->
            <input 
                type="number" 
                step="0.01" 
                class="border rounded-lg p-2 text-gray-700 focus:ring focus:ring-blue-300 w-24"
                placeholder=""
            >
        </div>
    @endforeach
</div>