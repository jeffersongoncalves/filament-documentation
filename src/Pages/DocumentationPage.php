<?php

namespace JeffersonGoncalves\FilamentDocumentation\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use JeffersonGoncalves\FilamentDocumentation\FilamentDocumentationPlugin;
use JeffersonGoncalves\FilamentDocumentation\Services\DocumentationParser;
use JeffersonGoncalves\FilamentDocumentation\Services\NavigationBuilder;

class DocumentationPage extends Page
{
    protected string $view = 'filament-documentation::page';

    public string $pageSlug = '';

    public array $document = [];

    public array $navigation = [];

    public string $searchQuery = '';

    // ── Filament Navigation ───────────────────────────────────────────────

    public static function getNavigationLabel(): string
    {
        return FilamentDocumentationPlugin::get()->getNavigationLabel();
    }

    public static function getNavigationIcon(): string|\BackedEnum|Htmlable|null
    {
        return FilamentDocumentationPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentDocumentationPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return FilamentDocumentationPlugin::get()->getNavigationSort();
    }

    public static function getSlug(): string
    {
        return FilamentDocumentationPlugin::get()->getSlug().'/{pageSlug?}';
    }

    // ── Authorization ─────────────────────────────────────────────────────

    public static function canAccess(): bool
    {
        $plugin = FilamentDocumentationPlugin::get();

        if ($plugin->hasAuthorization()) {
            return auth()->user()?->can('viewDocumentation') ?? false;
        }

        return true;
    }

    // ── Lifecycle ─────────────────────────────────────────────────────────

    public function mount(string $pageSlug = ''): void
    {
        static::authorizeAccess();

        $this->pageSlug = $pageSlug ?: $this->resolveDefaultSlug();
        $this->loadDocument();
        $this->loadNavigation();
    }

    public function updatedPageSlug(): void
    {
        $this->loadDocument();
        $this->loadNavigation();
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    protected function resolveDefaultSlug(): string
    {
        $home = config('filament-documentation.home', 'home');

        return pathinfo($home, PATHINFO_FILENAME);
    }

    protected function loadDocument(): void
    {
        $docsPath = config('filament-documentation.docs_path');
        $filePath = $this->resolveFilePath($this->pageSlug, $docsPath);

        $this->document = app(DocumentationParser::class)->parse($filePath);

        $this->dispatch('docs-page-loaded', title: $this->document['title']);
    }

    protected function loadNavigation(): void
    {
        $builder = app(NavigationBuilder::class);
        $tree = $builder->build();
        $this->navigation = $builder->markActive($tree, $this->pageSlug);
    }

    protected function resolveFilePath(string $slug, string $docsPath): string
    {
        $direct = rtrim($docsPath, '/').'/'.$slug.'.md';
        if (file_exists($direct)) {
            return $direct;
        }

        $index = rtrim($docsPath, '/').'/'.$slug.'/index.md';
        if (file_exists($index)) {
            return $index;
        }

        return rtrim($docsPath, '/').'/'.config('filament-documentation.home', 'home.md');
    }

    public function navigateTo(string $slug): void
    {
        $this->pageSlug = $slug;
        $this->loadDocument();
        $this->loadNavigation();
    }

    public function getTitle(): string|Htmlable
    {
        return $this->document['title'] ?? config('filament-documentation.title', 'Documentation');
    }
}
