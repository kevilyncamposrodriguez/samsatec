@unless($column['hidden'])
    <div
        @if (isset($column['tooltip']['text'])) title="{{ $column['tooltip']['text'] }}" @endif
                                                class="relative table-cell h-12 overflow-hidden align-top" @include('datatables::style-width')>
        @if($column['sortable'])
            <button style="background-color: #192743;" wire:click="sort('{{ $index }}')" class="w-full h-full px-2  border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none @if($column['headerAlign'] === 'right') justify-end @elseif($column['headerAlign'] === 'center') justify-center @endif">
                <span style="color: white;" class="inline ">{{ str_replace('_', ' ', $column['label']) }}</span>
                <span class="inline text-xs text-blue-400">
                    @if($sort === $index)
                        @if($direction)
                            <x-icons.chevron-up wire:loading.remove class="w-6 h-6 text-green-600 stroke-current" />
                        @else
                            <x-icons.chevron-down wire:loading.remove class="w-6 h-6 text-green-600 stroke-current" />
                        @endif
                    @endif
                </span>
            </button>
        @else
            <div style="background-color: #192743;"class="w-full h-full px-2 py-1 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none @if($column['headerAlign'] === 'right') justify-end @elseif($column['headerAlign'] === 'center') justify-center @endif">
                <span class="inline"  style="color: white;">{{ str_replace('_', ' ', $column['label']) }}</span>
            </div>
        @endif
    </div>
@endif
