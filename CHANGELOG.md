# Changelog

All notable changes to `filament-documentation` will be documented in this file.

## v3.0.6 - 2026-03-03

### What's Changed

- **Fix:** Make `DocumentationPage::routes()` method signature compatible with Filament's `PageConfiguration` parameter

## 3.0.5 - 2026-02-24

### What's Changed

- Add Laravel 13.x support (CI test matrix updated for Laravel 13)

## v3.0.4 - 2026-02-22

### Fixed

- Use `DocumentationPage::getUrl()` for markdown link generation instead of manual URL concatenation, fixing malformed URLs when panel path is empty

## v3.0.3 - 2026-02-21

### Changed

- Removed non-functional search component
- Added URL-based navigation with history.pushState for page sharing
- Override routes() to allow encoded forward slashes in pageSlug parameter

## v3.0.2 - 2026-02-21

### Fixed

- Replace Tailwind utility classes with custom CSS for Filament v4/v5 compatibility
- Fix dark code blocks appearing in light mode (highlight.js theme conflict)
- Switch dark mode to `.dark` class selector (Filament standard)

## v3.0.1 - 2026-02-21

### Fixed

- Use `<x-filament::icon>` component instead of direct `<x-heroicon-*>` in sidebar navigation, fixing `InvalidArgumentException: Unable to locate a class or view for component [heroicon-m-chevron-right]` caused by Filament's `DisableBladeIconComponents` middleware

**Full Changelog**: https://github.com/jeffersongoncalves/filament-documentation/compare/v3.0.0...v3.0.1

## v3.0.0 - 2026-02-21

### Initial Release (Filament v5)

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
- Laravel 12+
- Filament 5.x
- Livewire 4.x
