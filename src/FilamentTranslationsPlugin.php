<?php

namespace Madbox99\FilamentTranslations;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Madbox99\FilamentTranslations\Pages\ManageTranslations;

class FilamentTranslationsPlugin implements Plugin
{
    protected ?string $navigationGroup = 'Settings';

    protected ?string $navigationIcon = 'heroicon-o-language';

    protected ?int $navigationSort = 10;

    public function getId(): string
    {
        return 'filament-translations';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationIcon(?string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationSort(?int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function getNavigationIcon(): ?string
    {
        return $this->navigationIcon;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            ManageTranslations::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
