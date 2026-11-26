<?php

use JeffersonGoncalves\FilamentDocumentation\Pages\DocumentationPage;

function resolveDocPath(string $slug): string
{
    $docsPath = __DIR__.'/fixtures/docs';

    return (fn () => $this->resolveFilePath($slug, $docsPath))->call(new DocumentationPage);
}

it('resolves pages inside the docs path', function () {
    expect(realpath(resolveDocPath('installation')))->toBe(realpath(__DIR__.'/fixtures/docs/installation.md'))
        ->and(realpath(resolveDocPath('advanced/overview')))->toBe(realpath(__DIR__.'/fixtures/docs/advanced/overview.md'));
});

it('falls back to the home page for a slug that leaves the docs path', function (string $slug) {
    expect(realpath(resolveDocPath($slug)))->toBe(realpath(__DIR__.'/fixtures/docs/home.md'));
})->with([
    'parent directory' => ['../outside'],
    'nested traversal' => ['advanced/../../outside'],
    'missing page' => ['does-not-exist'],
]);
