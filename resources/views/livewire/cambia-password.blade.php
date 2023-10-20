<div>

    <div 
        class="px-4 py-2 text-white border-2 rounded-lg" 
        style="width: 600px; color: hsl(324, 37%, 71%); background-color: #000000;"
    >
        <div style="margin-top: 6px; padding: 20px;">

            <h1 style="margin-top: 12px;" class="text-xl font-bold">Datos del Usuario</h1>
            <h2 style="margin-left: 12px; margin-top: 12px;" class="font-mono text-lg font-base">UserId: {{ $user->id }}</h2>
            <h2 style="margin-left: 12px; margin-top: 6px;" class="font-mono text-lg font-base">Nombre: {{ $user->name }}</h2>
            <h2 style="margin-left: 12px; margin-top: 6px;" class="font-mono text-lg font-base">Cuenta: {{ $user->email }}</h2>

            <form action="#" style="margin-left: 12px; margin-top: 20px; margin-bottom: 12px;">

                <label for="palabra">Nueva Password:</label>
                
                <input  type="text" 
                        id="palabra" 
                        name="palabra" 
                        wire:model="palabra"
                        style="width: 50%;
                        padding: 12px 20px;
                        margin-left: 8px;
                        display: inline-block;
                        box-sizing: border-box;
                        border: 2px solid #ccc;
                        border-radius: 4px;
                        background-color: #000;
                        color: #fff;"
                >
                
                <div style="margin-left: 220px; margin-top: 24px; margin-bottom: 12px;">
                    {{ $this->cambiarAction }}
                </div>

            </form>
            
        </div>

    </div>

    <x-filament-actions::modals />

</div>
