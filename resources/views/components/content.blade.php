<article class="docs-article">
    {{-- Title --}}
    <header class="docs-article-header">
        <h1 class="docs-article-title">
            {{ $document['title'] }}
        </h1>
    </header>

    {{-- Markdown HTML content --}}
    <div class="docs-body">
        {!! $document['html'] !!}
    </div>
</article>
