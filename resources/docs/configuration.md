---
title: "Configuration"
path: configuration
order: 3
---

# Configuration

After publishing the config file, you can customize the plugin behavior.

## Config File

```bash
php artisan vendor:publish --tag=filament-documentation-config
```

This will create `config/filament-documentation.php` with the following options:

| Option | Default | Description |
|--------|---------|-------------|
| `title` | `Documentation` | Default title when no H1 or frontmatter title is found |
| `docs_path` | `resource_path('docs')` | Directory where .md files are located |
| `home` | `home.md` | Default file when no slug is provided |
| `cache_minutes` | `10` | Cache time for parsed content (0 to disable) |
| `login_route` | `null` | Route for unauthorized access redirect |

## Plugin Options

```php
FilamentDocumentationPlugin::make()
    ->slug('docs')                          // URL: /admin/docs
    ->navigationLabel('Documentation')      // Sidebar label
    ->navigationIcon('heroicon-o-book-open') // Sidebar icon
    ->navigationGroup('Help')               // Group in sidebar
    ->navigationSort(99)                    // Sort order
    ->withAuthorization(false)              // Require authorization policy
```

## Frontmatter

Each `.md` file supports YAML frontmatter:

```yaml
---
title: "Page Title"
path: custom-slug
order: 1
---
```

| Key | Description |
|-----|-------------|
| `title` | Page title (overrides first H1) |
| `path` | Custom URL slug |
| `order` | Sort order in the sidebar navigation |
