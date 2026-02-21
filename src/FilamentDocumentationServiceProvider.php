<?php

namespace JeffersonGoncalves\FilamentDocumentation;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use JeffersonGoncalves\FilamentDocumentation\Commands\InstallCommand;
use JeffersonGoncalves\FilamentDocumentation\Services\DocumentationParser;
use JeffersonGoncalves\FilamentDocumentation\Services\NavigationBuilder;

class FilamentDocumentationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-documentation.php',
            'filament-documentation'
        );

        $this->app->singleton(DocumentationParser::class);
        $this->app->singleton(NavigationBuilder::class);
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('filament-documentation-styles', __DIR__.'/../resources/css/documentation.css')
                ->loadedOnRequest(),
            Js::make('filament-documentation-scripts', __DIR__.'/../resources/js/documentation.js')
                ->loadedOnRequest(),
        ], package: 'jeffersongoncalves/filament-documentation');

        $this->publishes([
            __DIR__.'/../config/filament-documentation.php' => config_path('filament-documentation.php'),
        ], 'filament-documentation-config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-documentation');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-documentation'),
        ], 'filament-documentation-views');

        $this->publishes([
            __DIR__.'/../resources/docs' => resource_path('docs'),
        ], 'filament-documentation-docs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
