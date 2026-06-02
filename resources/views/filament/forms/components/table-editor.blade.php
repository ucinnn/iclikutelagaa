@php
    $rowCount = $rowCount ?? 1;
    $colCount = $colCount ?? 1;
    $tableData = $tableData ?? [];
@endphp

<div class="flex justify-center w-full overflow-x-auto">
    <table class="border border-gray-300 rounded-lg min-w-max text-sm">
        <tbody>
            @for ($i = 0; $i < $rowCount; $i++)
                <tr>
                    @for ($j = 0; $j < $colCount; $j++)
                        <td class="border border-gray-300 p-2">
                            <input
                                type="text"
                                wire:model.defer="{{ ($getStatePath()) }}.table_data.{{ $i }}.{{ $j }}"
                                class="w-full border-none focus:ring-0 text-center"
                                placeholder="..."
                            />
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>
