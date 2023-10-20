<div>
    <form wire:submit="edit">

        {{ $this->form }}

        <button type="submit" class="bg-pink-800 my-12 ml-12 px-8 py-4">
            APLICAR
        </button>
    </form>

    <x-filament-actions::modals />
</div>
