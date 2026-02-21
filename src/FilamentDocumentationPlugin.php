<?php

namespace JeffersonGoncalves\FilamentDocumentation;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentDocumentation\Pages\DocumentationPage;

class FilamentDocumentationPlugin implements Plugin
{
    protected string $slug = 'docs';

    protected string $navigationLabel = 'Documentation';

    protected string $navigationIcon = 'heroicon-o-book-open';

    protected ?string $navigationGroup = null;

    protected int $navigationSort = 99;

    protected bool $authorization = false;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-documentation';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            DocumentationPage::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    // ── Fluent configurators ──────────────────────────────────────────────

    public function slug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function navigationLabel(string $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function getNavigationLabel(): string
    {
        return $this->navigationLabel;
    }

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function getNavigationIcon(): string
    {
        return $this->navigationIcon;
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function navigationSort(int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationSort(): int
    {
        return $this->navigationSort;
    }

    public function withAuthorization(bool $enabled = true): static
    {
        $this->authorization = $enabled;

        return $this;
    }

    public function hasAuthorization(): bool
    {
        return $this->authorization;
    }
}
