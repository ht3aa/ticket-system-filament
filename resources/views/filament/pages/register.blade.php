<x-filament-panels::page.simple>
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-8 space-y-6 dark:bg-gray-800">
        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            ابدأ رحلتك مع <span class="font-bold text-orange-600">جني سيارات</span>. أدخل بيانات نشاطك التجاري، وسيتم التواصل معك بعد مراجعة الطلب.
        </p>

        <x-filament-panels::form id="form" wire:submit.prevent="register">
            <div class="space-y-6">
                <!-- Render the Form -->
                {{ $this->form }}
            </div>

        </x-filament-panels::form>
    </div>
</x-filament-panels::page.simple>
