<!-- File to generate tables... -->
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach($columns as $col)
                    <th scope="col" class="px-6 py-3 rounded-s-lg">
                        {{ $col }}
                    </th>                
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($values as $val)
                <tr class="bg-white dark:bg-gray-800">
                    @foreach($columns as $col)
                        <td class="px-6 py-4">
                            {{ $val->$col }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
