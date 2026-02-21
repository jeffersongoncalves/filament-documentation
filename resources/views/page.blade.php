<x-filament-panels::page>
    <div
        class="docs-wrapper flex gap-6 min-h-screen"
        x-data="{ sidebarOpen: true }"
        x-load-css="[
            @js(\Filament\Support\Facades\FilamentAsset::getStyleHref('filament-documentation-styles', package: 'jeffersongoncalves/filament-documentation')),
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css'
        ]"
        data-dispatch="docs-assets"
        x-load-js="[
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js',
            @js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('filament-documentation-scripts', package: 'jeffersongoncalves/filament-documentation'))
        ]"
        x-on:docs-assets-js.window="$nextTick(() => initDocs())"
    >

        {{-- Sidebar --}}
        <aside class="docs-sidebar w-64 flex-shrink-0"
               :class="sidebarOpen ? 'block' : 'hidden lg:block'">

            {{-- Search --}}
            <div class="mb-4">
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="search"
                        placeholder="Search docs..."
                        wire:model.live.debounce.300ms="searchQuery"
                    />
                </x-filament::input.wrapper>
            </div>

            {{-- Navigation --}}
            @include('filament-documentation::components.sidebar', [
                'items'       => $navigation,
                'currentSlug' => $pageSlug,
            ])
        </aside>

        {{-- Main Content --}}
        <main class="docs-content flex-1 min-w-0">
            @include('filament-documentation::components.content', [
                'document' => $document,
            ])
        </main>

    </div>
</x-filament-panels::page>
