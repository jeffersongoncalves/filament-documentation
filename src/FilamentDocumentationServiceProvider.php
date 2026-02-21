<?php

namespace JeffersonGoncalves\FilamentDocumentation;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use JeffersonGoncalves\FilamentDocumentation\Commands\InstallCommand;
use JeffersonGoncalves\FilamentDocumentation\Services\DocumentationParser;
use JeffersonGoncalves\FilamentDocumentation\Services\NavigationBuilder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentDocumentationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-documentation')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(InstallCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(DocumentationParser::class);
        $this->app->singleton(NavigationBuilder::class);
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        $this->publishes([
            __DIR__.'/../resources/docs' => resource_path('docs'),
        ], 'filament-documentation-docs');
    }

    protected function getAssetPackageName(): ?string
    {
        return 'jeffersongoncalves/filament-documentation';
    }

    protected function getAssets(): array
    {
        return [
            Css::make('filament-documentation-styles', __DIR__.'/../resources/dist/filament-documentation.css')
                ->loadedOnRequest(),
            Js::make('filament-documentation-scripts', __DIR__.'/../resources/dist/filament-documentation.js')
                ->loadedOnRequest(),
        ];
    }
}
