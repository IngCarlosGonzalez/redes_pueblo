<x-guest-layout>

    <x-authentication-card>
        
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <span class="ml-0 mb-8 text-xl text-gray-600 dark:text-gray-400">Favor de Ingresar sus Datos...</span>

            <div style="padding: 12px 20px;
            margin: 8px 2px;
            display: inline-block;
            box-sizing: border-box;
            border: 2px solid #000099;
            border-radius: 4px;
            background-color: #000;
            color: #999;"
            class="mb-8">
                <span class="ml-0 text-sm text-gray-600 dark:text-gray-400">Cuenta de Correo</span>
                <input 
                id="email" 
                class="block mt-1 w-full text-2xl font-bold" 
                style="background-color: #aaa; color: #000099;"
                type="email" 
                name="email" 
                :value="old('email')" 
                required autofocus 
                autocomplete="username" 
                />
            </div>

            <div 
            class="mt-4"
            style="padding: 12px 20px;
            margin: 8px 2px;
            display: inline-block;
            box-sizing: border-box;
            border: 2px solid #000099;
            border-radius: 4px;
            background-color: #000;
            color: #999;"
            class="mb-8">
                <span class="ml-0 text-sm text-gray-600 dark:text-gray-400">Teclear Contraseña</span>
                <input 
                id="password" 
                class="block mt-1 w-full text-2xl font-bold" 
                style="background-color: #aaa; color: #000099;"
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                {{-- @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif --}}

                <button 
                class="ml-4" 
                type="submit"
                style="padding: 12px 20px;
                margin: 8px 2px;
                display: inline-block;
                box-sizing: border-box;
                border: 2px solid #0000ff;
                border-radius: 4px;
                background-color: #000077;
                color: #fff;">
                    INGRESAR
                </button>

            </div>

        </form>

    </x-authentication-card>

</x-guest-layout>
