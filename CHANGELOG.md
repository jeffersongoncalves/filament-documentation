# Changelog

All notable changes to `filament-documentation` will be documented in this file.

## v1.0.0 - 2026-02-21

### Initial Release (Filament v3)

- Markdown-based documentation inside Filament admin panels
- YAML frontmatter support (title, path, order)
- GitHub Flavored Markdown rendering with CommonMark
- Syntax highlighting via highlight.js with copy-to-clipboard
- Dynamic sidebar with nested directory support (collapsible groups)
- Heading permalinks for deep linking
- Relative `.md` links auto-converted to panel routes
- Light and dark mode support
- Authorization control via `viewDocumentation` gate
- Configurable cache for parsed content
- Artisan install command (`php artisan docs:install`)
- Assets lazy-loaded via `x-load-css` / `x-load-js`
- ServiceProvider using `spatie/laravel-package-tools`

#### Requirements

- PHP 8.1+
- Laravel 10+
- Filament 3.x
- Livewire 3.x
