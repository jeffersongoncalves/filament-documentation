<?php

use JeffersonGoncalves\FilamentDocumentation\Services\NavigationBuilder;

beforeEach(function () {
    $this->builder = app(NavigationBuilder::class);
    $this->fixturesPath = __DIR__.'/fixtures/docs';
});

it('builds navigation tree from docs directory', function () {
    $tree = $this->builder->build($this->fixturesPath);

    expect($tree)->toBeArray()->not->toBeEmpty();
});

it('includes files as navigation items', function () {
    $tree = $this->builder->build($this->fixturesPath);

    $fileItems = collect($tree)->where('type', 'file');

    expect($fileItems)->not->toBeEmpty();

    $homeItem = $fileItems->firstWhere('slug', 'home');
    expect($homeItem)
        ->not->toBeNull()
        ->and($homeItem['title'])->toBe('Getting Started')
        ->and($homeItem['type'])->toBe('file');
});

it('includes directories as navigation groups', function () {
    $tree = $this->builder->build($this->fixturesPath);

    $dirItems = collect($tree)->where('type', 'directory');

    expect($dirItems)->not->toBeEmpty();

    $advancedDir = $dirItems->first();
    expect($advancedDir['title'])->toBe('Advanced')
        ->and($advancedDir['children'])->not->toBeEmpty();
});

it('sorts items by order', function () {
    $tree = $this->builder->build($this->fixturesPath);

    $fileItems = collect($tree)->where('type', 'file');
    $orders = $fileItems->pluck('order')->values()->toArray();

    $sorted = $orders;
    sort($sorted);

    expect($orders)->toBe($sorted);
});

it('sorts directory children by order', function () {
    $tree = $this->builder->build($this->fixturesPath);

    $advancedDir = collect($tree)->firstWhere('type', 'directory');
    $childOrders = collect($advancedDir['children'])->pluck('order')->values()->toArray();

    $sorted = $childOrders;
    sort($sorted);

    expect($childOrders)->toBe($sorted);
});

it('marks active item correctly', function () {
    $tree = $this->builder->build($this->fixturesPath);
    $markedTree = $this->builder->markActive($tree, 'home');

    $homeItem = collect($markedTree)->firstWhere('slug', 'home');

    expect($homeItem['active'])->toBeTrue();
});

it('marks non-active items as inactive', function () {
    $tree = $this->builder->build($this->fixturesPath);
    $markedTree = $this->builder->markActive($tree, 'home');

    $installItem = collect($markedTree)->firstWhere('slug', 'installation');

    expect($installItem['active'])->toBeFalse();
});

it('opens parent directory when child is active', function () {
    $tree = $this->builder->build($this->fixturesPath);
    $markedTree = $this->builder->markActive($tree, 'advanced/overview');

    $advancedDir = collect($markedTree)->firstWhere('type', 'directory');

    expect($advancedDir['open'])->toBeTrue();

    $activeChild = collect($advancedDir['children'])->firstWhere('slug', 'advanced/overview');
    expect($activeChild['active'])->toBeTrue();
});

it('keeps parent directory closed when no child is active', function () {
    $tree = $this->builder->build($this->fixturesPath);
    $markedTree = $this->builder->markActive($tree, 'home');

    $advancedDir = collect($markedTree)->firstWhere('type', 'directory');

    expect($advancedDir['open'])->toBeFalse();
});

it('formats directory names correctly', function () {
    $tree = $this->builder->build($this->fixturesPath);

    $dirItem = collect($tree)->firstWhere('type', 'directory');

    expect($dirItem['title'])->toBe('Advanced');
});

it('returns empty array for non-existent directory', function () {
    $tree = $this->builder->build('/non/existent/path');

    expect($tree)->toBeArray()->toBeEmpty();
});
