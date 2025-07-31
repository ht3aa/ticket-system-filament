<x-filament-panels::page>
        <x-filament-panels::form wire:submit.prevent="saveSettings">
            {{ $this->form }}

            <x-filament::button color="primary" type="submit">
                حفظ التعديلات
            </x-filament::button>
        </x-filament-panels::form>
</x-filament-panels::page>