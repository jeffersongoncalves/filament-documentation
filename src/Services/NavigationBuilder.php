<?php

namespace JeffersonGoncalves\FilamentDocumentation\Services;

use Illuminate\Support\Facades\Cache;

class NavigationBuilder
{
    public function __construct(
        protected DocumentationParser $parser
    ) {}

    /**
     * Build the navigation tree from the docs directory.
     */
    public function build(?string $docsPath = null): array
    {
        $docsPath ??= config('filament-documentation.docs_path');

        $cacheMinutes = config('filament-documentation.cache_minutes', 10);

        if ($cacheMinutes <= 0) {
            return $this->scanDirectory($docsPath, $docsPath);
        }

        $cacheKey = 'filament_docs_nav_'.md5($docsPath);

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), fn () => $this->scanDirectory($docsPath, $docsPath));
    }

    protected function scanDirectory(string $dir, string $rootDir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $items = collect(scandir($dir))
            ->reject(fn ($f) => str_starts_with($f, '.'))
            ->map(fn ($f) => $dir.DIRECTORY_SEPARATOR.$f);

        $dirs = $items->filter(fn ($f) => is_dir($f));
        $files = $items->filter(fn ($f) => is_file($f) && str_ends_with($f, '.md'));

        $nodes = collect();

        foreach ($files as $file) {
            $parsed = $this->parser->parse($file);
            $relative = ltrim(str_replace(realpath($rootDir), '', realpath($file)), '/\\');
            $relative = str_replace('\\', '/', $relative);
            $slug = pathinfo($relative, PATHINFO_DIRNAME).'/'.pathinfo($relative, PATHINFO_FILENAME);
            $slug = ltrim(str_replace('//', '/', $slug), './');

            $nodes->push([
                'type' => 'file',
                'title' => $parsed['title'],
                'slug' => $slug,
                'order' => $parsed['order'],
                'path' => $parsed['path'] ?? $slug,
                'active' => false,
                'children' => [],
            ]);
        }

        foreach ($dirs as $subDir) {
            $dirName = basename($subDir);
            $children = $this->scanDirectory($subDir, $rootDir);

            if (! empty($children)) {
                $nodes->push([
                    'type' => 'directory',
                    'title' => $this->formatDirectoryName($dirName),
                    'slug' => null,
                    'order' => $this->directoryOrder($children),
                    'path' => null,
                    'active' => false,
                    'children' => $children,
                ]);
            }
        }

        return $nodes->sortBy('order')->values()->toArray();
    }

    protected function formatDirectoryName(string $name): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }

    protected function directoryOrder(array $children): int
    {
        return collect($children)->min('order') ?? 999;
    }

    /**
     * Mark the active item in the navigation tree based on the current slug.
     */
    public function markActive(array $tree, string $currentSlug): array
    {
        return array_map(function ($node) use ($currentSlug) {
            $node['active'] = ($node['slug'] === $currentSlug || $node['path'] === $currentSlug);

            if (! empty($node['children'])) {
                $node['children'] = $this->markActive($node['children'], $currentSlug);
                $node['open'] = collect($node['children'])->contains(fn ($child) => $child['active'] || ($child['open'] ?? false));
            }

            return $node;
        }, $tree);
    }
}
