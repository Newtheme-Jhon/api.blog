<div {{$attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-4'])}} >

    <div class="div-text px-4 sm:px-0">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{$title}}</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                {{$description}}
            </p>
        </div>
    </div>

    <div class="div-form mt-5 md:mt-0 md:col-span-2">
        <div>
            <div class="p-6 bg-white dark:bg-gray-800 shadow 
            {{isset($actions) ? 'sm:rounded-tr-md sm:rounded-tl-md' : 'sm:rounded-md'}}">
                <!--Aquí el codigo del form-->
                {{$slot}}
            </div>

            @isset($actions)
            <div class="p-6 bg-gray-100 dark:bg-gray-700 shadow flex justify-end items-center rounded-br-md rounded-bl-md">
                {{$actions}}
            </div>
            @endisset
        </div>
    </div>

</div>