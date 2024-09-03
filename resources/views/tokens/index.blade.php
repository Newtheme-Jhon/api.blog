<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Api Tokens') }}
        </h2>
    </x-slot>

    <div id="app">
        <x-container class="py-8">

            <!--crear tokens-->
            <x-form-section class="mb-12">
                <x-slot name="title">
                    Access Token
                </x-slot>
                <x-slot name="description">
                    Aquí se podra generar un access token
                </x-slot>

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <!--errors-->
                        <div v-if="form.errors.length > 0" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Opps! </strong>
                            <span>¡Algo salio mal!</span>
                            <ul>
                                <li v-for="error in form.errors">@{{ error }}</li>
                            </ul>
                        </div>
                        <div>
                            <x-input-label>Nombre</x-input-label>
                            <x-text-input v-model="form.name" type="text" class="w-full mt-1"></x-text-input>
                        </div>

                        <!--scopes-->
                        <div v-if="scopes.length > 0">
                            <x-input-label>Scopes</x-input-label>
                            <div v-for="scope in scopes">
                                <x-input-label>
                                    <input type="checkbox" name="scopes" id="scopes" :value="scope.id" v-model="form.scopes">
                                    @{{scope.description}}
                                </x-input-label>
                            </div>
                        </div>
                    </div>
                </div>
                <x-slot name="actions">
                    <x-primary-button v-on:click="store" v-bind:disabled="form.disabled">Crear</x-primary-button>
                </x-slot>
            </x-form-section>

             <!--mostrar lista de tokens-->
             <x-form-section v-if="tokens.length > 0">

                <x-slot name="title">
                    Lista de tokens
                </x-slot>
                <x-slot name="description">
                    Aquí se mostrarán todos los tokens que se han creado
                </x-slot>

                <div>
                    <table class="text-gray-200">
                        <thead class="border-b border-gray-400">
                            <tr class="text-left">
                                <th class="py-2 w-full">Nombre</th>
                                <th class="py-2">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-400">

                            <tr v-for="token in tokens">
                                <td class="py-2">@{{token.name}}</td>
                                <td class="flex divide-x divide-gray-300 py-2">
                                    <a v-on:click="show(token)" class="pr-2 hover:text-blue-600 font-semibold cursor-pointer">Ver</a>
                                    <a v-on:click="revoke(token)" class="px-2 hover:text-blue-600 font-semibold cursor-pointer">Eliminar</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
    
            </x-form-section>

        </x-container>

        <!--Modal mostrar token-->
        <x-app-modal modal="showToken.open">

            @slot('title')
                Mostrar Access token
            @endslot

            @slot('content')

                <div class="space-y-6 overflow-auto">

                    <p>
                        <span class="font-semibold">ACCESS TOKEN: </span>
                        <span v-text="showToken.id"></span>
                    </p>
                   
                </div>

            @endslot

            @slot('footer')
                <button v-on:click="showToken.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cerrar
                </button>
            @endslot

        </x-app-modal>

    </div>

@push('js')
    <script>
        const {createApp} = Vue

        const app = createApp({
            data(){
                return{
                    tokens: [],
                    form: {
                        name: '',
                        scopes: [],
                        errors: [],
                        disabled: false
                    },
                    showToken: {
                        open: false,
                        id: ''
                    },
                    scopes: []
                }
            },
            mounted(){
                this.getTokens();
                this.getScopes();
            },
            methods: {
                getTokens(){
                    axios.get('/oauth/personal-access-tokens')
                    .then(response => {
                        this.tokens = response.data;
                    })
                },
                store(){

                    this.form.disabled = true;

                    axios.post('/oauth/personal-access-tokens', this.form)
                    .then(response =>{
                        this.form.name = '';
                        this.form.errors = [];
                        this.form.disabled = false;
                        this.getTokens();
                        this.form.scopes = [];
                    })
                    .catch(error => {
                        this.form.errors = Object.values(error.response.data.errors).flat();
                        this.form.disabled = false;
                    })
                },
                revoke(token){

                    Swal.fire({
                        title: "Eliminar el access token",
                        text: "Despues de eliminar el token ya no se podra recuperar",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si deseo Eliminarlo"
                    }).then((result) => {
                        if (result.isConfirmed) {

                            axios.delete('/oauth/personal-access-tokens/' + token.id)
                            .then(response => {
                                this.getTokens();
                            })

                            Swal.fire({
                                title: "Token Eliminado",
                                text: "El Token ha sido eliminado",
                                icon: "success"
                            });
                        }
                    });

                },
                show(token){
                    this.showToken.open = true;
                    this.showToken.id = token.id;
                },
                getScopes(){
                    axios.get('/oauth/scopes')
                    .then(response =>{
                        this.scopes = response.data;
                        //console.log(this.scopes)
                    })
                }
            }
        })

        app.mount('#app')

    </script>
@endpush
</x-app-layout>
