<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: flex-end;">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                {{ __('cms.settings_cluster.save') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
