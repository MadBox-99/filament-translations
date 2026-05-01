<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center justify-between">
            <div>
                {{ $this->addLocaleAction }}
            </div>
            <x-filament::button type="submit">
                Save
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
