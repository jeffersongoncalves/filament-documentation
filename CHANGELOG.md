# Changelog

All notable changes to `filament-documentation` will be documented in this file.

## v1.0.2 - 2026-02-21

### Fixed

- Replace Tailwind utility classes with custom CSS for Filament v4/v5 compatibility
- Fix dark code blocks appearing in light mode (highlight.js theme conflict)
- Switch dark mode to `.dark` class selector (Filament standard)

## v1.0.1 - 2026-02-21

### Fixed

- Use `<x-filament::icon>` component instead of direct `<x-heroicon-*>` in sidebar navigation, fixing `InvalidArgumentException: Unable to locate a class or view for component [heroicon-m-chevron-right]` caused by Filament's `DisableBladeIconComponents` middleware

**Full Changelog**: https://github.com/jeffersongoncalves/filament-documentation/compare/v1.0.0...v1.0.1

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
