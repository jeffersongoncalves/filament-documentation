<div class="filament-hidden">

![Filament Documentation](https://raw.githubusercontent.com/jeffersongoncalves/filament-documentation/3.x/art/jeffersongoncalves-filament-documentation.png)

</div>

# Filament Documentation Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-documentation.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-documentation)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-documentation/tests.yml?branch=3.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-documentation/actions?query=workflow%3Atests+branch%3A3.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-documentation/fix-php-code-style-issues.yml?branch=3.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-documentation/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3A3.x)

A Filament plugin to add **markdown-based documentation** directly inside your admin panel. Inspired by `nova-documentation`.

## Features

- Read `.md` files from a configurable directory (`resources/docs/`)
- Parse YAML frontmatter for title, order, and custom path
- Render Markdown with GitHub Flavored Markdown support
- Syntax highlighting via highlight.js with copy-to-clipboard
- Dynamic sidebar with nested directory support (collapsible groups)
- Heading permalinks for deep linking
- Relative `.md` links auto-converted to panel routes
- Light and dark mode support
- Authorization control via policies
- Configurable cache for parsed content
- Artisan install command (`php artisan docs:install`)

## Version Compatibility

| Branch | Filament | Laravel | PHP | Livewire |
|--------|----------|---------|-----|----------|
| 1.x | 3.x | 10+ | 8.1+ | 3.x |
| 2.x | 4.x | 11+ | 8.2+ | 3.x |
| **3.x** | **5.x** | **12+** | **8.2+** | **4.x** |

## Screenshots

<!-- SCREENSHOTS -->
| Screenshot | Light | Dark |
|---|---|---|
| Docs home | ![docs-home](screenshots/light/docs-home.png) | ![docs-home](screenshots/dark/docs-home.png) |
| Docs installation | ![docs-installation](screenshots/light/docs-installation.png) | ![docs-installation](screenshots/dark/docs-installation.png) |
| Docs configuration | ![docs-configuration](screenshots/light/docs-configuration.png) | ![docs-configuration](screenshots/dark/docs-configuration.png) |
| Docs advanced | ![docs-advanced](screenshots/light/docs-advanced.png) | ![docs-advanced](screenshots/dark/docs-advanced.png) |
<!-- SCREENSHOTS -->

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/filament-documentation
```

Optionally, publish the config and example docs:

```bash
php artisan docs:install
```

Or publish individually:

```bash
php artisan vendor:publish --tag=filament-documentation-config
php artisan vendor:publish --tag=filament-documentation-docs
```

## Setup

Register the plugin in your `PanelProvider`:

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

## Writing Documentation

Place your `.md` files in `resources/docs/` (configurable via `config/filament-documentation.php`).

### Frontmatter

Each file supports optional YAML frontmatter:

```yaml
---
title: "Getting Started"
path: home
order: 1
---
```

| Key | Description |
|-----|-------------|
| `title` | Page title (overrides first H1) |
| `path` | Custom URL slug |
| `order` | Sort order in sidebar |

### Nested Directories

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

### Relative Links

Link between docs using relative `.md` paths — they are automatically converted to panel routes:

```markdown
- [Installation](installation.md)
- [Advanced](advanced/overview.md)
```

## Configuration

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

## Plugin Options

| Method | Default | Description |
|--------|---------|-------------|
| `slug()` | `'docs'` | URL path: `/admin/docs` |
| `navigationLabel()` | `'Documentation'` | Sidebar label |
| `navigationIcon()` | `heroicon-o-book-open` | Sidebar icon |
| `navigationGroup()` | `null` | Sidebar group |
| `navigationSort()` | `99` | Sort order |
| `withAuthorization()` | `false` | Require `viewDocumentation` gate |

## Authorization

Enable authorization to restrict access:

```php
FilamentDocumentationPlugin::make()
    ->withAuthorization(true)
```

Then define a gate:

```php
Gate::define('viewDocumentation', function ($user) {
    return $user->hasRole('admin');
});
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please see [SECURITY](.github/SECURITY.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
