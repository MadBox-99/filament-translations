<?php

declare(strict_types=1);

namespace Madbox99\FilamentTranslations\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Madbox99\FilamentTranslations\FilamentTranslationsServiceProvider;
use Madbox99\FilamentTranslations\Tests\Fixtures\AdminPanelProvider;
use Madbox99\FilamentTranslations\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected string $langPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(new User(['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com']));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->langPath);

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentTranslationsServiceProvider::class,
            AdminPanelProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $this->langPath = sys_get_temp_dir().'/filament-translations-'.uniqid();

        File::ensureDirectoryExists($this->langPath);
        File::put($this->langPath.'/en.json', json_encode(['Hello' => 'Hello']));
        File::put($this->langPath.'/hu.json', json_encode(['Hello' => 'Szia', 'Path' => 'a/b']));

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('filament-translations.lang_path', $this->langPath);
    }
}
