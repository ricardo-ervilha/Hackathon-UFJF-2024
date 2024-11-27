<form action="{{ route('csv.update') }}" method="POST">
    @csrf
    <input value="{{ $name }}" name="table_name" type="text" hidden>
    @foreach ($collection as $key => $item)
        <div class="">
            <label>{{ $key }}</label>
            <input name="{{ $key . "_type"}}" type="text" value="{{ $item }}">
            <div class="flex items-center mb-4">
                <input type="checkbox" name="{{ $key . "_checkbox" }}">
                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Feature de Tempo</label>
            </div>
            <div class="flex items-center mb-4">
                <input type="checkbox" name="{{ $key . "_americanformat" }}">
                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">É formato americano ?</label>
            </div>
            <div>
                <label>Digite o separador, caso seja uma data.</label>
                <input name="{{ $key . "_separator"}}" type="text" place>
            </div>
        </div>
    @endforeach
    <button class="bg-blue-400" type="submit">Submeter as alterações</button>
</form>