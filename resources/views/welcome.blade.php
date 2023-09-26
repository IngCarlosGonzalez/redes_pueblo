<x-guest-layout>

    <div class="py-12">

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="h-full overflow-hidden bg-black shadow-xl sm:rounded-lg">

                <div class="flex justify-center">

                    <div class="grid grid-cols-1 md:grid-cols-4 ">

                        <h2 class="mx-auto my-8 text-3xl font-semibold leading-tight text-white md:col-span-4 animate-bounce">
                            Sistema Redes Pueblo
                        </h2>

                        <div class="mx-auto mb-2 md:col-span-4 animate-pulse">
                            <img src="{{ asset('/logos/gente.jpg') }}" alt="Redes-Pueblo" width="600">
                        </div>
                        
                        <div class="mt-12 ml-24 space-x-8 md:col-span-4 md:ml-48 lg:ml-60">
                            <div width="36" class="h-16 mb-6 ml-8 w-60 ">
                                <a class="box-border h-16 p-4 border-4 rounded-md w-60 hover:scale-125 hover:bg-zinc-400 hover:text-black text-amber-700" href="/admin">ADMIN</a>
                            </div>
                            <div width="36" class="h-16 mb-6 ml-4 w-60 ">
                                <a class="box-border h-16 p-4 border-4 rounded-md w-60 hover:scale-125 hover:bg-zinc-400 hover:text-black text-violet-700" href="/coord">COORD</a>
                            </div>
                            <div width="36" class="h-16 mb-6 ml-4 w-60 ">
                                <a class="box-border h-16 p-4 border-4 rounded-md w-60 hover:scale-125 hover:bg-zinc-400 hover:text-black text-lime-700" href="/opers">OPERS</a>
                            </div>
                            <div width="36" class="h-16 mb-6 ml-4 w-60 ">
                                <a class="box-border h-16 p-4 border-4 rounded-md w-60 hover:scale-125 hover:bg-zinc-400 hover:text-black text-cyan-700" href="/proms">PROMs</a>
                            </div>
                        </div>
                            
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
