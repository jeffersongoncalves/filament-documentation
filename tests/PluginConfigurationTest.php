<?php

use JeffersonGoncalves\FilamentDocumentation\FilamentDocumentationPlugin;

it('creates plugin instance via make()', function () {
    $plugin = FilamentDocumentationPlugin::make();

    expect($plugin)->toBeInstanceOf(FilamentDocumentationPlugin::class);
});

it('has correct default values', function () {
    $plugin = FilamentDocumentationPlugin::make();

    expect($plugin->getSlug())->toBe('docs')
        ->and($plugin->getNavigationLabel())->toBe('Documentation')
        ->and($plugin->getNavigationGroup())->toBeNull()
        ->and($plugin->getNavigationSort())->toBe(99)
        ->and($plugin->hasAuthorization())->toBeFalse();
});

it('has correct plugin id', function () {
    $plugin = FilamentDocumentationPlugin::make();

    expect($plugin->getId())->toBe('filament-documentation');
});

it('sets slug via fluent api', function () {
    $plugin = FilamentDocumentationPlugin::make()->slug('documentation');

    expect($plugin->getSlug())->toBe('documentation');
});

it('sets navigation label via fluent api', function () {
    $plugin = FilamentDocumentationPlugin::make()->navigationLabel('Docs');

    expect($plugin->getNavigationLabel())->toBe('Docs');
});

it('sets navigation icon via fluent api', function () {
    $plugin = FilamentDocumentationPlugin::make()->navigationIcon('heroicon-o-document');

    expect($plugin->getNavigationIcon())->toBe('heroicon-o-document');
});

it('sets navigation group via fluent api', function () {
    $plugin = FilamentDocumentationPlugin::make()->navigationGroup('Help');

    expect($plugin->getNavigationGroup())->toBe('Help');
});

it('sets navigation sort via fluent api', function () {
    $plugin = FilamentDocumentationPlugin::make()->navigationSort(5);

    expect($plugin->getNavigationSort())->toBe(5);
});

it('sets authorization via fluent api', function () {
    $plugin = FilamentDocumentationPlugin::make()->withAuthorization(true);

    expect($plugin->hasAuthorization())->toBeTrue();
});

it('supports method chaining', function () {
    $plugin = FilamentDocumentationPlugin::make()
        ->slug('help')
        ->navigationLabel('Help Center')
        ->navigationGroup('Support')
        ->navigationSort(10)
        ->withAuthorization(true);

    expect($plugin->getSlug())->toBe('help')
        ->and($plugin->getNavigationLabel())->toBe('Help Center')
        ->and($plugin->getNavigationGroup())->toBe('Support')
        ->and($plugin->getNavigationSort())->toBe(10)
        ->and($plugin->hasAuthorization())->toBeTrue();
});

it('loads config values correctly', function () {
    expect(config('filament-documentation.docs_path'))->not->toBeNull()
        ->and(config('filament-documentation.home'))->toBe('home.md')
        ->and(config('filament-documentation.cache_minutes'))->toBe(0);
});
