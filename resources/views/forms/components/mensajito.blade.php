<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
 
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div wire:ignore>
            <input 
                style="width: 95%; margin-left: 20px; font-size: 24px; color: hsl(66, 80%, 51%); background-color: #000000;" 
                x-model="state" 
            />
        </div>
    </div>
    
</x-dynamic-component>
