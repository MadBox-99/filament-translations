<?php

declare(strict_types=1);

namespace Madbox99\FilamentTranslations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTranslationsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-translations';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews();
    }
}
