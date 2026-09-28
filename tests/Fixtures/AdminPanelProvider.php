<?php

declare(strict_types=1);

namespace Madbox99\FilamentTranslations\Tests\Fixtures;

use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Madbox99\FilamentTranslations\FilamentTranslationsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugin(FilamentTranslationsPlugin::make())
            ->middleware([
                EncryptCookies::class,
                StartSession::class,
                ShareErrorsFromSession::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
