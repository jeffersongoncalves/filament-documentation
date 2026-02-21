<?php

namespace JeffersonGoncalves\FilamentDocumentation\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'docs:install';

    protected $description = 'Install the Filament Documentation Plugin';

    public function handle(): int
    {
        $this->info('Installing Filament Documentation Plugin...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'filament-documentation-config',
            '--force' => false,
        ]);

        if ($this->confirm('Would you like to publish example documentation files?', true)) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'filament-documentation-docs',
            ]);
            $this->line('  Example docs published to resources/docs/');
        }

        $this->newLine();
        $this->info('Plugin installed successfully!');
        $this->line('  Register the plugin in your PanelProvider:');
        $this->newLine();
        $this->line('  FilamentDocumentationPlugin::make()');
        $this->line("      ->navigationLabel('Docs')");
        $this->line("      ->navigationIcon('heroicon-o-book-open')");
        $this->newLine();

        return self::SUCCESS;
    }
}
