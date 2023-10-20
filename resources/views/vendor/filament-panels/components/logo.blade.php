@if (filled($brand = filament()->getBrandName()))
    <div
        {{
            $attributes->class([
                'fi-logo text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white',
            ])
        }}
    >
        <div class="flex flex-row items-center p-2 m-2 space-x-12">
            <img src="/logos/exotico2.jpg" alt="Logo" class="h-10">
            &nbsp; Redes del Pueblo
        </div>
    </div>
@endif
