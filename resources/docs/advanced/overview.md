---
title: "Advanced Overview"
path: advanced/overview
order: 1
---

# Advanced Usage Overview

This section covers advanced topics for power users.

## Authorization

Enable authorization to restrict access to documentation:

```php
FilamentDocumentationPlugin::make()
    ->withAuthorization(true)
```

Then create a policy or gate for `viewDocumentation`:

```php
Gate::define('viewDocumentation', function ($user) {
    return $user->hasRole('admin');
});
```

## Custom Docs Path

You can point to any directory:

```php
// config/filament-documentation.php
'docs_path' => base_path('docs'),
```

## Nested Directories

Organize your docs in subdirectories for grouped navigation:

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

Subdirectories automatically become collapsible groups in the sidebar.
