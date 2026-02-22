<?php

namespace JeffersonGoncalves\FilamentDocumentation\Services;

use Illuminate\Support\Facades\Cache;
use JeffersonGoncalves\FilamentDocumentation\Pages\DocumentationPage;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Yaml;

class DocumentationParser
{
    protected MarkdownConverter $converter;

    public function __construct()
    {
        $environment = new Environment([
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => '',
                'fragment_prefix' => '',
                'title' => '',
                'insert' => 'after',
                'symbol' => '#',
            ],
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new HeadingPermalinkExtension);

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Parse a .md file and return structured data.
     */
    public function parse(string $filePath): array
    {
        if (! file_exists($filePath)) {
            return $this->emptyDocument();
        }

        $cacheMinutes = config('filament-documentation.cache_minutes', 10);

        if ($cacheMinutes <= 0) {
            return $this->parseFile($filePath);
        }

        $cacheKey = 'filament_docs_'.md5($filePath.filemtime($filePath));

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), fn () => $this->parseFile($filePath));
    }

    protected function parseFile(string $filePath): array
    {
        $rawContent = file_get_contents($filePath);

        $frontmatter = $this->extractFrontmatter($rawContent);
        $body = $this->stripFrontmatter($rawContent);

        $html = (string) $this->converter->convert($body);

        $title = $frontmatter['title'] ?? $this->extractFirstH1($body);

        $html = $this->processCodeBlocks($html);
        $html = $this->processLinks($html, $filePath);

        return [
            'title' => $title ?: pathinfo($filePath, PATHINFO_FILENAME),
            'html' => $html,
            'frontmatter' => $frontmatter,
            'path' => $frontmatter['path'] ?? null,
            'order' => $frontmatter['order'] ?? 999,
        ];
    }

    protected function extractFrontmatter(string $content): array
    {
        if (! str_starts_with(ltrim($content), '---')) {
            return [];
        }

        preg_match('/^---\s*\n(.*?)\n---\s*\n/s', ltrim($content), $matches);

        if (empty($matches[1])) {
            return [];
        }

        try {
            return Yaml::parse($matches[1]) ?? [];
        } catch (\Exception) {
            return [];
        }
    }

    protected function stripFrontmatter(string $content): string
    {
        return preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', ltrim($content)) ?? $content;
    }

    protected function extractFirstH1(string $markdown): string
    {
        preg_match('/^#\s+(.+)$/m', $markdown, $matches);

        return trim($matches[1] ?? '');
    }

    protected function processCodeBlocks(string $html): string
    {
        return preg_replace_callback(
            '/<code class="language-([^"]+)">/',
            fn ($m) => "<code class=\"language-{$m[1]}\" data-lang=\"{$m[1]}\">",
            $html
        ) ?? $html;
    }

    protected function processLinks(string $html, string $currentFile): string
    {
        $docsRoot = config('filament-documentation.docs_path');

        if (! $docsRoot || ! is_dir($docsRoot)) {
            return $html;
        }

        return preg_replace_callback(
            '/<a\s+href="([^"]+\.md)"/',
            function ($matches) use ($docsRoot, $currentFile) {
                $link = $matches[1];
                $dir = dirname($currentFile);
                $absPath = realpath($dir.'/'.$link);

                if ($absPath && str_starts_with($absPath, realpath($docsRoot))) {
                    $relative = ltrim(str_replace(realpath($docsRoot), '', $absPath), '/\\');
                    $slug = str_replace(['/', '\\', '.md'], ['/', '/', ''], $relative);

                    try {
                        $url = DocumentationPage::getUrl(['pageSlug' => $slug]);
                    } catch (\Throwable) {
                        $url = '/docs/'.$slug;
                    }

                    return "<a href=\"{$url}\"";
                }

                return $matches[0];
            },
            $html
        ) ?? $html;
    }

    protected function emptyDocument(): array
    {
        return [
            'title' => 'Page not found',
            'html' => '<p class="text-danger-500">The requested documentation page was not found.</p>',
            'frontmatter' => [],
            'path' => null,
            'order' => 999,
        ];
    }
}
