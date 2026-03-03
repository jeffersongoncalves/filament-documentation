## Filament Documentation

A Filament plugin that adds markdown-based documentation pages inside your admin panel. Supports YAML frontmatter, nested directory navigation, GitHub Flavored Markdown, syntax highlighting with copy-to-clipboard, heading permalinks, relative link conversion, caching, and authorization. Requires Filament 5.0+ and PHP 8.2+.

### Installation

@verbatim
<code-snippet name="Install the plugin" lang="bash">
composer require jeffersongoncalves/filament-documentation
</code-snippet>
@endverbatim

### Install Command

@verbatim
<code-snippet name="Run install command for config and example docs" lang="bash">
php artisan docs:install
</code-snippet>
@endverbatim

### Register Plugin

@verbatim
<code-snippet name="Register in PanelProvider" lang="php">
use JeffersonGoncalves\FilamentDocumentation\FilamentDocumentationPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentDocumentationPlugin::make()
                ->slug('docs')
                ->navigationLabel('Documentation')
                ->navigationIcon('heroicon-o-book-open')
                ->navigationGroup('Help')
                ->navigationSort(99)
                ->withAuthorization(false),
        ]);
}
</code-snippet>
@endverbatim

### Writing Docs

@verbatim
<code-snippet name="Markdown file with frontmatter" lang="markdown">
---
title: "Getting Started"
path: home
order: 1
---

# Getting Started

Your documentation content here...
</code-snippet>
@endverbatim

### Key Methods

- `slug(string $slug)` - URL path segment (default: `'docs'`, results in `/admin/docs`)
- `navigationLabel(string $label)` - Sidebar label (default: `'Documentation'`)
- `navigationIcon(string|BackedEnum $icon)` - Sidebar icon (default: `heroicon-o-book-open`)
- `navigationGroup(?string $group)` - Sidebar group (default: `null`)
- `navigationSort(int $sort)` - Sort order (default: `99`)
- `withAuthorization(bool $enabled)` - Require `viewDocumentation` gate (default: `false`)

### Configuration

- Config file: `config/filament-documentation.php`
- `title` - Default page title (env: `DOCS_TITLE`)
- `docs_path` - Markdown files directory (default: `resource_path('docs')`)
- `home` - Home file name (default: `'home.md'`)
- `cache_minutes` - Cache TTL in minutes (env: `DOCS_CACHE`, default: `10`, `0` to disable)

### Architecture

- **Namespace**: `JeffersonGoncalves\FilamentDocumentation`
- **Plugin**: `FilamentDocumentationPlugin` implements `Filament\Contracts\Plugin`
- **Page**: `DocumentationPage` extends `Filament\Pages\Page`
- **Services**: `DocumentationParser` (Markdown + frontmatter), `NavigationBuilder` (sidebar tree)
- **Install Command**: `docs:install` artisan command

### Best Practices

- Place `.md` files in `resources/docs/` with optional subdirectories for collapsible sidebar groups
- Use YAML frontmatter for `title`, `path`, and `order` to control sidebar appearance
- Use relative `.md` links between docs -- they auto-convert to panel routes
- Enable caching in production (`DOCS_CACHE=10`) and disable in development (`DOCS_CACHE=0`)
- Use `withAuthorization(true)` with a `viewDocumentation` gate to restrict access
