---
name: documentation-development
description: Build and work with the Filament Documentation plugin, including markdown-based docs pages, YAML frontmatter, nested navigation, syntax highlighting, heading permalinks, caching, and authorization.
---

# Documentation Plugin Development

## When to use this skill

Use this skill when:
- Adding markdown-based documentation pages inside a Filament admin panel
- Configuring the documentation sidebar navigation, slug, and icons
- Writing documentation with YAML frontmatter and nested directories
- Setting up authorization for documentation access
- Customizing caching, link resolution, or markdown parsing
- Troubleshooting page rendering, navigation, or routing issues

## Architecture

### Namespace
```
JeffersonGoncalves\FilamentDocumentation
```

### Key Classes

| Class | Path | Description |
|-------|------|-------------|
| `FilamentDocumentationPlugin` | `src/FilamentDocumentationPlugin.php` | Plugin class implementing `Filament\Contracts\Plugin` |
| `DocumentationPage` | `src/Pages/DocumentationPage.php` | Filament page extending `Filament\Pages\Page` |
| `DocumentationParser` | `src/Services/DocumentationParser.php` | Parses `.md` files with frontmatter, GFM, and heading permalinks |
| `NavigationBuilder` | `src/Services/NavigationBuilder.php` | Builds sidebar navigation tree from the docs directory |
| `InstallCommand` | `src/Commands/InstallCommand.php` | `docs:install` artisan command |
| `FilamentDocumentationServiceProvider` | `src/FilamentDocumentationServiceProvider.php` | Service provider for config, views, commands |

### Dependencies
- `filament/filament: ^5.0`
- `league/commonmark: ^2.4` (Markdown parsing with GFM and extensions)
- `spatie/yaml-front-matter: ^2.0` (YAML frontmatter extraction)
- `spatie/laravel-package-tools: ^1.16`

## Configuration

### Install Command

```bash
php artisan docs:install
```

This command:
1. Publishes the config file
2. Optionally publishes example documentation files to `resources/docs/`

### Manual Publishing

```bash
# Config only
php artisan vendor:publish --tag=filament-documentation-config

# Example docs only
php artisan vendor:publish --tag=filament-documentation-docs
```

### Config File

```php
// config/filament-documentation.php
return [
    'title'         => env('DOCS_TITLE', 'Documentation'),
    'docs_path'     => resource_path('docs'),
    'home'          => 'home.md',
    'cache_minutes' => env('DOCS_CACHE', 10),  // 0 to disable
    'login_route'   => null,
];
```

## Features

### Plugin Registration

```php
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
```

### Plugin API Reference

| Method | Type | Default | Description |
|--------|------|---------|-------------|
| `slug(string $slug)` | `string` | `'docs'` | URL path: `/admin/{slug}` |
| `navigationLabel(string $label)` | `string` | `'Documentation'` | Sidebar label |
| `navigationIcon(string\|BackedEnum $icon)` | `string\|BackedEnum` | `Heroicon::OutlinedBookOpen` | Sidebar icon |
| `navigationGroup(?string $group)` | `?string` | `null` | Sidebar group |
| `navigationSort(int $sort)` | `int` | `99` | Sort order |
| `withAuthorization(bool $enabled)` | `bool` | `false` | Enable `viewDocumentation` gate check |

### Writing Documentation

Place `.md` files in `resources/docs/` (configurable via `docs_path`).

#### Frontmatter

```yaml
---
title: "Getting Started"
path: home
order: 1
---
```

| Key | Description |
|-----|-------------|
| `title` | Page title (overrides first H1 heading) |
| `path` | Custom URL slug |
| `order` | Sort order in sidebar navigation |

#### Nested Directories

Subdirectories become collapsible groups in the sidebar:

```
resources/docs/
├── home.md
├── installation.md
├── configuration.md
└── advanced/
    ├── overview.md
    ├── authorization.md
    └── customization.md
```

Directory names are formatted automatically: `advanced` becomes `Advanced`, `getting-started` becomes `Getting Started`.

#### Relative Links

Link between docs using relative `.md` paths -- they auto-convert to panel routes:

```markdown
- [Installation](installation.md)
- [Advanced](advanced/overview.md)
```

### Authorization

Enable authorization to restrict documentation access:

```php
FilamentDocumentationPlugin::make()
    ->withAuthorization(true)
```

Then define a gate in your `AuthServiceProvider` or a policy:

```php
use Illuminate\Support\Facades\Gate;

Gate::define('viewDocumentation', function ($user) {
    return $user->hasRole('admin');
});
```

When authorization is enabled, `DocumentationPage::canAccess()` checks `auth()->user()->can('viewDocumentation')`.

## Internal Behavior

### DocumentationParser

The parser uses League CommonMark with these extensions:
- `CommonMarkCoreExtension` -- Standard Markdown
- `GithubFlavoredMarkdownExtension` -- Tables, strikethrough, autolinks, task lists
- `FrontMatterExtension` -- YAML frontmatter extraction
- `HeadingPermalinkExtension` -- Anchor links on headings

Processing pipeline:
1. Extract YAML frontmatter via regex
2. Strip frontmatter from raw content
3. Convert Markdown to HTML via `MarkdownConverter`
4. Extract title from frontmatter or first H1
5. Add `data-lang` attributes to code blocks for syntax highlighting
6. Convert relative `.md` links to panel routes via `DocumentationPage::getUrl()`

#### Caching

```php
// Cache key includes file path + modification time
$cacheKey = 'filament_docs_' . md5($filePath . filemtime($filePath));

// Cached for configured minutes (default: 10)
Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), fn () => $this->parseFile($filePath));
```

Set `DOCS_CACHE=0` to disable caching during development.

### NavigationBuilder

Builds a recursive navigation tree by scanning the docs directory:

1. Lists all `.md` files and subdirectories
2. Parses each file to extract title, order, path, and slug
3. Subdirectories become `type: 'directory'` nodes with `children`
4. Files become `type: 'file'` nodes
5. Sorts by `order` (from frontmatter, default: `999`)
6. `markActive()` sets `active` and `open` flags based on current slug

Navigation node structure:
```php
[
    'type'     => 'file',        // 'file' or 'directory'
    'title'    => 'Getting Started',
    'slug'     => 'home',
    'order'    => 1,
    'path'     => 'home',
    'active'   => true,
    'children' => [],
]
```

### DocumentationPage

The page component:
- Route: `{slug}/{pageSlug?}` with `where('pageSlug', '.*')` for nested paths
- `mount()`: Resolves the default slug from config `home` setting, loads document and navigation
- `navigateTo(string $slug)`: Livewire method for SPA-like navigation with `window.history.pushState`
- `resolveFilePath()`: Tries `{slug}.md` first, then `{slug}/index.md`, then falls back to `home.md`

### File Resolution Order

```php
// 1. Direct file match
$docsPath . '/' . $slug . '.md'

// 2. Directory index
$docsPath . '/' . $slug . '/index.md'

// 3. Fallback to home
$docsPath . '/' . config('filament-documentation.home', 'home.md')
```

## Troubleshooting

### Page Not Found / Shows "Page not found" Message
**Cause**: The `.md` file does not exist at the expected path.
**Solution**: Verify the file exists in `resources/docs/` (or your configured `docs_path`). Check the slug matches the filename without `.md` extension.

### Navigation Sidebar Empty
**Cause**: No `.md` files in the configured `docs_path`, or directory does not exist.
**Solution**: Run `php artisan docs:install` to publish example docs, or create `.md` files in `resources/docs/`.

### Stale Content After Editing
**Cause**: Cached parsed content.
**Solution**: Set `DOCS_CACHE=0` in `.env` during development, or clear cache with `php artisan cache:clear`.

### Relative Links Not Working
**Cause**: Linked `.md` file does not exist, or path is outside the docs root.
**Solution**: Ensure the linked file exists and the relative path resolves within `resources/docs/`. The link converter uses `realpath()` and validates the result is within `docs_path`.

### Authorization Blocking Access
**Cause**: `withAuthorization(true)` is enabled but no `viewDocumentation` gate is defined.
**Solution**: Define the gate in your `AuthServiceProvider`:
```php
Gate::define('viewDocumentation', fn ($user) => true);
```

### Route Conflicts
**Cause**: The `slug` conflicts with another Filament page route.
**Solution**: Change the slug: `FilamentDocumentationPlugin::make()->slug('documentation')`.
