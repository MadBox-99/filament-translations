<?php

declare(strict_types=1);

namespace Madbox99\FilamentTranslations\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Madbox99\FilamentTranslations\FilamentTranslationsPlugin;
use UnitEnum;

class ManageTranslations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament-translations::pages.manage-translations';

    public ?string $locale = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    #[\Override]
    public static function getNavigationLabel(): string
    {
        return __('filament-translations::translations.navigation_label');
    }

    #[\Override]
    public function getTitle(): string
    {
        return __('filament-translations::translations.title');
    }

    #[\Override]
    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (filament()->hasPlugin('filament-translations')) {
            /** @var FilamentTranslationsPlugin $plugin */
            $plugin = filament()->getPlugin('filament-translations');

            return $plugin->getNavigationGroup() ?? static::$navigationGroup;
        }

        return static::$navigationGroup;
    }

    #[\Override]
    public static function getNavigationIcon(): string|BackedEnum|null
    {
        if (filament()->hasPlugin('filament-translations')) {
            /** @var FilamentTranslationsPlugin $plugin */
            $plugin = filament()->getPlugin('filament-translations');

            return $plugin->getNavigationIcon() ?? static::$navigationIcon;
        }

        return static::$navigationIcon;
    }

    #[\Override]
    public static function getNavigationSort(): ?int
    {
        if (filament()->hasPlugin('filament-translations')) {
            /** @var FilamentTranslationsPlugin $plugin */
            $plugin = filament()->getPlugin('filament-translations');

            return $plugin->getNavigationSort() ?? static::$navigationSort;
        }

        return static::$navigationSort;
    }

    public function mount(): void
    {
        $locales = $this->getAvailableLocales();
        $this->locale = $locales[0] ?? app()->getLocale();

        $this->loadTranslations();
    }

    public function form(Form|Schema $form): Form|Schema
    {
        return $form
            ->schema([
                Schemas\Components\Section::make(__('filament-translations::translations.section'))
                    ->schema([
                        Forms\Components\Select::make('locale')
                            ->label(__('filament-translations::translations.fields.locale'))
                            ->options(fn (): array => collect($this->getAvailableLocales())
                                ->mapWithKeys(fn (string $locale): array => [$locale => strtoupper($locale)])
                                ->toArray())
                            ->live()
                            ->afterStateUpdated(function (?string $state): void {
                                $this->locale = $state ?? $this->locale;
                                $this->loadTranslations();
                            }),
                        Forms\Components\KeyValue::make('translations')
                            ->label(__('filament-translations::translations.fields.translations'))
                            ->keyLabel(__('filament-translations::translations.fields.key'))
                            ->valueLabel(__('filament-translations::translations.fields.value'))
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $locale = $this->locale;
        $translations = $data['translations'] ?? [];

        $path = $this->getLangPath("{$locale}.json");

        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n");

        Notification::make()->title(__('filament-translations::translations.notifications.saved'))->success()->send();
    }

    public function addLocale(): void
    {
        // This is handled via the addLocaleAction modal
    }

    public function addLocaleAction(): Action
    {
        return Action::make('addLocale')
            ->label(__('filament-translations::translations.actions.add_locale'))
            ->icon('heroicon-o-plus')
            ->schema([
                Forms\Components\TextInput::make('new_locale')
                    ->label(__('filament-translations::translations.fields.new_locale'))
                    ->placeholder(__('filament-translations::translations.fields.new_locale_placeholder'))
                    ->required()
                    ->maxLength(5)
                    ->alphaDash(),
            ])
            ->action(function (array $data): void {
                $locale = strtolower($data['new_locale']);
                $path = $this->getLangPath("{$locale}.json");

                if (File::exists($path)) {
                    Notification::make()->title(__('filament-translations::translations.notifications.locale_exists'))->warning()->send();

                    return;
                }

                File::ensureDirectoryExists(dirname($path));
                File::put($path, "{}\n");

                $this->locale = $locale;
                $this->loadTranslations();

                Notification::make()->title(__('filament-translations::translations.notifications.locale_added', ['locale' => $locale]))->success()->send();
            });
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label(__('filament-translations::translations.actions.save'))->submit('save'),
        ];
    }

    protected function loadTranslations(): void
    {
        $path = $this->getLangPath("{$this->locale}.json");

        $translations = [];
        if (File::exists($path)) {
            $translations = json_decode(File::get($path), true) ?? [];
        }

        $this->data['locale'] = $this->locale;
        $this->data['translations'] = $translations;
    }

    /** @return array<string> */
    protected function getAvailableLocales(): array
    {
        $langPath = $this->getLangPath();

        if (! File::isDirectory($langPath)) {
            return [app()->getLocale()];
        }

        return collect(File::files($langPath))
            ->filter(fn ($file) => $file->getExtension() === 'json')
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->sort()
            ->values()
            ->toArray();
    }

    protected function getLangPath(string $path = ''): string
    {
        $basePath = config('filament-translations.lang_path', lang_path());

        return $path ? "{$basePath}/{$path}" : $basePath;
    }
}
