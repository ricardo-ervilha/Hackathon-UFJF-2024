<form action="" method="">
    @foreach ($collection as $key => $item)
        <div class="">
            <label>{{ $key }}</label>
            <input type="text" value="{{ $item }}">
            <div class="flex items-center mb-4">
                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Feature de Tempo</label>
            </div>
            <div>
                <label>Digite o separador, caso seja uma data.</label>
                <input type="text" place>
            </div>
        </div>
    @endforeach
</form>