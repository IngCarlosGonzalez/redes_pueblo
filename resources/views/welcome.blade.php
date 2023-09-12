<x-guest-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-black  h-full overflow-hidden shadow-xl sm:rounded-lg">

                <div class="flex justify-center">

                    <div class="grid grid-cols-4 ">

                        <h2 class="col-span-4 animate-bounce my-12 mx-auto font-semibold text-3xl text-white leading-tight">
                            Sistema Redes Pueblo
                        </h2>

                        <div class="col-span-4 mx-auto animate-pulse ">
                            <img src="{{ asset('/logos/gente.jpg') }}" alt="Redes-Pueblo" width="600">
                        </div>
                        
                        <div class="inline-flex mt-12 md:ml-32 lg:ml-72 space-x-8">
                            
                            <a class="box-border rounded-md hover:scale-125 h-16 w-48 p-4 hover:bg-zinc-400 hover:text-black border-4 text-amber-700" href="/admin">Administrador</a>
                            
                            <a class="box-border rounded-md hover:scale-125 h-16 w-48 p-4 hover:bg-zinc-400 hover:text-black border-4 text-violet-700" href="/coord">Coordinador</a>
                            
                            <a class="box-border rounded-md hover:scale-125 h-16 w-48 p-4 hover:bg-zinc-400 hover:text-black border-4 text-lime-700" href="/opers">Operadores</a>
                            
                            <a class="box-border rounded-md hover:scale-125 h-16 w-48 p-4 hover:bg-zinc-400 hover:text-black border-4 text-cyan-700" href="/proms">Promotores</a>
                            
                        </div>
                            
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
