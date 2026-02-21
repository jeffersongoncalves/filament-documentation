<?php

namespace JeffersonGoncalves\FilamentDocumentation\Tests;

use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use JeffersonGoncalves\FilamentDocumentation\FilamentDocumentationServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            SupportServiceProvider::class,
            FilamentDocumentationServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('filament-documentation.docs_path', __DIR__.'/fixtures/docs');
        $app['config']->set('filament-documentation.home', 'home.md');
        $app['config']->set('filament-documentation.cache_minutes', 0);
    }
}
