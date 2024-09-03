<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div id="app">
        <x-container class="py-8">

            <!--crear clientes-->
            <x-form-section class="mb-12">

                <x-slot name="title">
                    Crear un nuevo cliente
                </x-slot>
                <x-slot name="description">
                    Ingresa los datos solicitados para crear un nuevo cliente
                </x-slot>

                <!--Aquí el codigo del form-->
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <div v-if="createForm.errors.length > 0" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Opps! </strong>
                            <span>¡Algo salio mal!</span>
                            <ul>
                                <li v-for="error in createForm.errors">@{{ error }}</li>
                            </ul>
                        </div>
                        <x-input-label>Nombre</x-input-label>
                        <x-text-input v-model="createForm.name" type="text" class="w-full mt-1"></x-text-input>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-input-label>URL de redirección</x-input-label>
                        <x-text-input v-model="createForm.redirect" type="text" class="w-full mt-1"></x-text-input>
                    </div>
                </div>

                <x-slot name="actions">
                    <x-primary-button v-bind:disabled="createForm.disabled" v-on:click="store">Crear</x-primary-button>
                </x-slot>

            </x-form-section>

            <!--mostrar clientes-->
            <x-form-section v-if="clients.length > 0">

                <x-slot name="title">
                    Lista de clientes
                </x-slot>
                <x-slot name="description">
                    Aquí se mostrarán todos los clientes que se han creado
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

                            <tr v-for="client in clients">
                                <td class="py-2">@{{client.name}}</td>
                                <td class="flex divide-x divide-gray-300 py-2">
                                    <a v-on:click="show(client)" class="pr-2 hover:text-blue-600 font-semibold cursor-pointer">Credenciales</a>
                                    <a v-on:click="edit(client)" class="px-2 hover:text-blue-600 font-semibold cursor-pointer">Editar</a>
                                    <a v-on:click="destroy(client)" class="px-2 hover:text-blue-600 font-semibold cursor-pointer">Eliminar</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
    
            </x-form-section>

        </x-container>

        <!--Modal-->
        <x-app-modal modal="editForm.open">

            @slot('title')
                Editar Cliente
            @endslot

            @slot('content')
                <!--Aquí el codigo del form-->
                <div class="space-y-6">
                    <div class="col-span-6 sm:col-span-4">
                        <div v-if="editForm.errors.length > 0" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Opps! </strong>
                            <span>¡Algo salio mal!</span>
                            <ul>
                                <li v-for="error in editForm.errors">@{{ error }}</li>
                            </ul>
                        </div>
                        <x-input-label class="!text-gray-500 text-left">Nombre</x-input-label>
                        <x-text-input v-model="editForm.name" type="text" class="w-full mt-1 !bg-white !text-black !border-gray-200"></x-text-input>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-input-label class="!text-gray-500 text-left">URL de redirección</x-input-label>
                        <x-text-input v-model="editForm.redirect" type="text" class="w-full mt-1 !bg-white !text-black !border-gray-200"></x-text-input>
                    </div>
                </div>
            @endslot

            @slot('footer')
                <button v-on:click="update()" v-bind:disabled="editForm.disabled" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-50">
                    Actualizar
                </button>
                <button v-on:click="editForm.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cancel
                </button>
            @endslot

        </x-app-modal>

        <!--Modal data credentials-->
        <x-app-modal modal="showClient.open">

            @slot('title')
                Mostrar Credenciales
            @endslot

            @slot('content')

                <div class="space-y-6">
                    <p>
                        <span class="font-semibold">CIENTE: </span>
                        <span v-text="showClient.name"></span>
                    </p>
                    <p>
                        <span class="font-semibold">CIENT_ID: </span>
                        <span v-text="showClient.id"></span>
                    </p>
                    <p>
                        <span class="font-semibold">CIENT_SECRET: </span>
                        <span v-text="showClient.secret"></span>
                    </p>
                </div>

            @endslot

            @slot('footer')
                <button v-on:click="showClient.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cerrar
                </button>
            @endslot

        </x-app-modal>
    </div>

@push('js')
    <script>

        const {createApp} = Vue;

        const app = createApp({

            data() {
                return {
                    clients: [],
                    createForm: {
                        errors: [],
                        disabled: false,
                        errors: [],
                        name: null,
                        redirect: null
                    },
                    editForm: {
                        id: null,
                        open: false,
                        errors: [],
                        disabled: false,
                        errors: [],
                        name: null,
                        redirect: null
                    },
                    showClient: {
                        open: false,
                        id: null,
                        name: null,
                        secret: null
                    }
                }
            },
            mounted(){
                this.getClients();
            },
            methods: {
                getClients: function(){

                    axios.get('/oauth/clients')
                    .then(response => {
                        this.clients = response.data;
                    })
                },
                store: function(){

                    this.createForm.disabled = true;

                    axios.post('/oauth/clients', this.createForm)
                    .then(response => {
                        this.createForm.name = null;
                        this.createForm.redirect = null;
                        this.createForm.errors = [];

                        Swal.fire({
                            title: "Petición Exitosa",
                            text: "Se ha creado el cliente correctamente",
                            icon: "success",
                            confirmButtonText: "cerrar"
                        });

                        this.getClients();

                        this.createForm.disabled = false;

                    }).catch(error => {
                        this.createForm.errors = Object.values(error.response.data.errors).flat();
                        this.createForm.disabled = false;
                    })
                },
                destroy(client){

                    Swal.fire({
                        title: "Eliminar el cliente",
                        text: "Despues de eliminar el cliente ya no se podra recuperar",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si deseo Eliminarlo"
                    }).then((result) => {
                        if (result.isConfirmed) {

                            axios.delete('/oauth/clients/' + client.id)
                            .then(response => {
                                this.getClients();
                            })

                            Swal.fire({
                                title: "Cliente Eliminado",
                                text: "El registro del cliente ha sido eliminado",
                                icon: "success"
                            });
                        }
                    });

                },
                edit: function(client){
                    this.editForm.open = true;
                    this.editForm.errors = [];

                    this.editForm.id = client.id;
                    this.editForm.name = client.name;
                    this.editForm.redirect = client.redirect;
                }, 
                update(){

                    this.editForm.disabled = true;
                    //console.log(this.editForm)

                    axios.put('/oauth/clients/' + this.editForm.id, this.editForm)
                    .then(response => {

                        this.editForm.open = false;
                        this.editForm.disabled = false;
                        this.editForm.name = null;
                        this.editForm.redirect = null;
                        this.editForm.errors = [];

                        Swal.fire({
                            title: "Petición Exitosa",
                            text: "El cliente se ha actualizado correctamente",
                            icon: "success",
                            confirmButtonText: "cerrar"
                        });

                        this.getClients();

                        this.editForm.disabled = false;

                    }).catch(error => {
                        this.editForm.errors = Object.values(error.response.data.errors).flat();
                        this.editForm.disabled = false;
                    })

                },
                show: function(client){

                    this.showClient.open = true;
                    this.showClient.id = client.id;
                    this.showClient.name = client.name;
                    this.showClient.secret = client.secret;

                }
            }

        })

        app.mount('#app');

    </script>
@endpush

</x-app-layout>