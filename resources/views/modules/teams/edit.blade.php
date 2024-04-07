<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Edit Team') }}
    </h2>
  </x-slot>

  <!-- Aquí puedes crear el formulario para editar un rol existente -->
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

        <form method="post" action="{{ route('teams.update', $team->id) }}">
            @csrf
            @method('put')

            <!-- Name -->
            <div>
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" value="{{ $team->name }}" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ms-4">
                {{ __('Update') }}
              </x-primary-button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
