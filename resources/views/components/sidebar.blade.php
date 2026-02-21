<nav class="docs-nav space-y-1">
    @foreach ($items as $item)
        @if ($item['type'] === 'directory')
            {{-- Directory group --}}
            <div x-data="{ open: {{ $item['open'] ?? false ? 'true' : 'false' }} }">
                <button
                    @click="open = !open"
                    class="docs-nav-group flex items-center justify-between w-full px-3 py-2 text-sm font-semibold rounded-lg
                           text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 transition">
                    <span>{{ $item['title'] }}</span>
                    <x-filament::icon icon="heroicon-m-chevron-right" class="w-4 h-4 transition-transform" x-bind:class="open ? 'rotate-90' : ''" />
                </button>

                <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                    @include('filament-documentation::components.sidebar', [
                        'items'       => $item['children'],
                        'currentSlug' => $currentSlug,
                    ])
                </div>
            </div>

        @else
            {{-- Page link --}}
            <button
                wire:click="navigateTo('{{ $item['slug'] }}')"
                @class([
                    'docs-nav-link flex items-center w-full px-3 py-2 text-sm rounded-lg text-left transition',
                    'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 font-medium' => $item['active'],
                    'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5' => ! $item['active'],
                ])>
                @if ($item['active'])
                    <span class="mr-2 w-1 h-4 rounded-full bg-primary-500 inline-block"></span>
                @else
                    <span class="mr-2 w-1 h-4 inline-block"></span>
                @endif
                {{ $item['title'] }}
            </button>
        @endif
    @endforeach
</nav>
