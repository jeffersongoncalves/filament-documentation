<article class="docs-article">
    {{-- Title --}}
    <header class="mb-6 pb-4 border-b border-gray-200 dark:border-white/10">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $document['title'] }}
        </h1>
    </header>

    {{-- Markdown HTML content --}}
    <div class="docs-body prose dark:prose-invert max-w-none">
        {!! $document['html'] !!}
    </div>
</article>
