---
title: "Installation"
path: installation
order: 2
---

# Installation

## Requirements

- PHP 8.2+
- Laravel 11+
- Filament 4.x

## Steps

```bash
composer require jeffersongoncalves/filament-documentation
php artisan docs:install
```

After installing, register the plugin in your `AdminPanelProvider`:

```php
use JeffersonGoncalves\FilamentDocumentation\FilamentDocumentationPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentDocumentationPlugin::make()
                ->slug('docs')
                ->navigationLabel('Documentation')
                ->navigationIcon('heroicon-o-book-open'),
        ]);
}
```
