<?php

namespace JeffersonGoncalves\FilamentDocumentation\Pages;

use Filament\Pages\Page;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;
use JeffersonGoncalves\FilamentDocumentation\FilamentDocumentationPlugin;
use JeffersonGoncalves\FilamentDocumentation\Services\DocumentationParser;
use JeffersonGoncalves\FilamentDocumentation\Services\NavigationBuilder;

class DocumentationPage extends Page
{
    protected string $view = 'filament-documentation::page';

    public string $pageSlug = '';

    public array $document = [];

    public array $navigation = [];

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

    public static function getSlug(?Panel $panel = null): string
    {
        return FilamentDocumentationPlugin::get()->getSlug().'/{pageSlug?}';
    }

    public static function routes(Panel $panel, ?\Filament\Pages\PageConfiguration $configuration = null): void
    {
        Route::get(static::getRoutePath($panel), static::class)
            ->middleware(static::getRouteMiddleware($panel))
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name(static::getRelativeRouteName($panel))
            ->where('pageSlug', '.*');
    }

    public static function canAccess(): bool
    {
        $plugin = FilamentDocumentationPlugin::get();

        if ($plugin->hasAuthorization()) {
            return auth()->user()?->can('viewDocumentation') ?? false;
        }

        return true;
    }

    public function mount(string $pageSlug = ''): void
    {
        $this->pageSlug = $pageSlug ?: $this->resolveDefaultSlug();
        $this->loadDocument();
        $this->loadNavigation();
    }

    public function updatedPageSlug(): void
    {
        $this->loadDocument();
        $this->loadNavigation();
    }

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

        $this->js("window.history.pushState({}, '', '".$this->getPageUrl($slug)."')");
    }

    public function getPageUrl(string $slug): string
    {
        return static::getUrl(['pageSlug' => $slug]);
    }

    public function getTitle(): string|Htmlable
    {
        return $this->document['title'] ?? config('filament-documentation.title', 'Documentation');
    }
}
