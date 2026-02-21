# Changelog

All notable changes to `filament-documentation` will be documented in this file.

## v2.0.0 - 2026-02-21

### Initial Release (Filament v4)

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
- Navigation icon supports `BackedEnum` (`Heroicon` enum)

#### Requirements

- PHP 8.2+
- Laravel 11+
- Filament 4.x
- Livewire 3.x
