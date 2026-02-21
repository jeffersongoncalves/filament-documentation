<nav class="docs-nav">
    @foreach ($items as $item)
        @if ($item['type'] === 'directory')
            {{-- Directory group --}}
            <div x-data="{ open: {{ $item['open'] ?? false ? 'true' : 'false' }} }">
                <button
                    @click="open = !open"
                    class="docs-nav-group">
                    <span>{{ $item['title'] }}</span>
                    <x-filament::icon icon="heroicon-m-chevron-right" class="docs-nav-chevron" x-bind:class="open ? 'docs-nav-chevron--open' : ''" />
                </button>

                <div x-show="open" x-collapse class="docs-nav-children">
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
                    'docs-nav-link',
                    'docs-nav-link--active' => $item['active'],
                    'docs-nav-link--inactive' => ! $item['active'],
                ])>
                <span class="docs-nav-indicator {{ $item['active'] ? 'docs-nav-indicator--active' : '' }}"></span>
                {{ $item['title'] }}
            </button>
        @endif
    @endforeach
</nav>
