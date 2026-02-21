<?php

use JeffersonGoncalves\FilamentDocumentation\Services\DocumentationParser;

beforeEach(function () {
    $this->parser = app(DocumentationParser::class);
    $this->fixturesPath = __DIR__.'/fixtures/docs';
});

it('parses a markdown file with frontmatter', function () {
    $result = $this->parser->parse($this->fixturesPath.'/home.md');

    expect($result)
        ->toBeArray()
        ->toHaveKeys(['title', 'html', 'frontmatter', 'path', 'order'])
        ->and($result['title'])->toBe('Getting Started')
        ->and($result['path'])->toBe('home')
        ->and($result['order'])->toBe(1)
        ->and($result['frontmatter'])->toHaveKey('title', 'Getting Started');
});

it('converts markdown to HTML', function () {
    $result = $this->parser->parse($this->fixturesPath.'/home.md');

    expect($result['html'])
        ->toContain('<h1')
        ->toContain('Getting Started')
        ->toContain('<h2')
        ->toContain('Quick Links');
});

it('handles code blocks with language attributes', function () {
    $result = $this->parser->parse($this->fixturesPath.'/installation.md');

    expect($result['html'])
        ->toContain('language-bash')
        ->toContain('data-lang="bash"');
});

it('handles inline code', function () {
    $result = $this->parser->parse($this->fixturesPath.'/installation.md');

    expect($result['html'])
        ->toContain('<code>code</code>');
});

it('parses file without frontmatter', function () {
    $result = $this->parser->parse($this->fixturesPath.'/no-frontmatter.md');

    expect($result['title'])->toBe('Page Without Frontmatter')
        ->and($result['frontmatter'])->toBeEmpty()
        ->and($result['path'])->toBeNull()
        ->and($result['order'])->toBe(999);
});

it('returns empty document for non-existent file', function () {
    $result = $this->parser->parse($this->fixturesPath.'/does-not-exist.md');

    expect($result['title'])->toBe('Page not found')
        ->and($result['html'])->toContain('not found');
});

it('parses blockquotes', function () {
    $result = $this->parser->parse($this->fixturesPath.'/advanced/overview.md');

    expect($result['html'])->toContain('<blockquote>');
});

it('parses tables', function () {
    $result = $this->parser->parse($this->fixturesPath.'/advanced/deep-dive.md');

    expect($result['html'])
        ->toContain('<table>')
        ->toContain('<th>')
        ->toContain('Column A')
        ->toContain('Value 1');
});

it('adds heading permalinks', function () {
    $result = $this->parser->parse($this->fixturesPath.'/home.md');

    expect($result['html'])->toContain('heading-permalink');
});

it('extracts title from frontmatter over H1', function () {
    $result = $this->parser->parse($this->fixturesPath.'/installation.md');

    expect($result['title'])->toBe('Installation Guide');
});

it('falls back to filename when no title found', function () {
    $emptyFile = $this->fixturesPath.'/empty-test-'.uniqid().'.md';
    file_put_contents($emptyFile, '');

    $result = $this->parser->parse($emptyFile);

    expect($result['title'])->not->toBeEmpty();

    unlink($emptyFile);
});
