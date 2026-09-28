<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Madbox99\FilamentTranslations\Pages\ManageTranslations;
use Madbox99\FilamentTranslations\Tests\Fixtures\CustomManageTranslations;

use function Pest\Livewire\livewire;

it('loads the first available locale', function (): void {
    livewire(ManageTranslations::class)
        ->assertSet('locale', 'en')
        ->assertSet('data.translations', ['Hello' => 'Hello']);
});

it('loads the selected locale when the language is switched', function (): void {
    livewire(ManageTranslations::class)
        ->fillForm(['locale' => 'hu'])
        ->assertSet('locale', 'hu')
        ->assertSet('data.translations', ['Hello' => 'Szia', 'Path' => 'a/b']);
});

it('saves into the selected locale file without escaping slashes', function (): void {
    livewire(ManageTranslations::class)
        ->fillForm(['locale' => 'hu'])
        ->fillForm(['translations' => ['Hello' => 'Helló', 'Path' => 'a/b']])
        ->call('save')
        ->assertNotified(__('filament-translations::translations.notifications.saved'));

    $contents = File::get($this->langPath.'/hu.json');

    expect(json_decode($contents, true))->toBe(['Hello' => 'Helló', 'Path' => 'a/b'])
        ->and($contents)->toContain('"a/b"')->toEndWith("\n")
        ->and(json_decode(File::get($this->langPath.'/en.json'), true))->toBe(['Hello' => 'Hello']);
});

it('adds a new locale as an empty json object', function (): void {
    livewire(ManageTranslations::class)
        ->callAction('addLocale', ['new_locale' => 'DE'])
        ->assertNotified(__('filament-translations::translations.notifications.locale_added', ['locale' => 'de']))
        ->assertSet('locale', 'de');

    expect(json_decode(File::get($this->langPath.'/de.json'), true))->toBe([]);
});

it('warns when the locale already exists', function (): void {
    livewire(ManageTranslations::class)
        ->callAction('addLocale', ['new_locale' => 'hu'])
        ->assertNotified(__('filament-translations::translations.notifications.locale_exists'));
});

it('translates its interface into hungarian', function (): void {
    app()->setLocale('hu');

    expect(ManageTranslations::getNavigationLabel())->toBe('Fordítások')
        ->and((new ManageTranslations)->getTitle())->toBe('Fordítások kezelése');

    livewire(ManageTranslations::class)
        ->assertSee('Nyelvi fájl')
        ->assertSee('Mentés')
        ->assertSee('Nyelv hozzáadása');
});

it('has the same keys in every package language file', function (): void {
    $flatten = fn (array $lines): array => array_keys(Arr::dot($lines));

    expect($flatten(require __DIR__.'/../../resources/lang/hu/translations.php'))
        ->toBe($flatten(require __DIR__.'/../../resources/lang/en/translations.php'));
});

it('can be subclassed with filament page signatures', function (): void {
    expect((new CustomManageTranslations)->getTitle())->toBe('Custom');
});
