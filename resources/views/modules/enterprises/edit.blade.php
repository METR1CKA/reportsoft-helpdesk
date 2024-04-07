<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Edit Enterprise') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form method="post" action="{{ route('enterprises.update', $enterprise->id) }}">
            @csrf
            @method('put')

            <!-- Contact Name -->
            <div>
              <x-input-label for="contact_name" :value="__('Contact Name')" />
              <x-text-input id="contact_name" class="block mt-1 w-full" type="text" name="contact_name" :value="old('contact_name', $enterprise->contact_name)" required autofocus autocomplete="contact_name" />
              <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
            </div>

            <!-- Contact Email -->
            <div class="mt-4">
              <x-input-label for="contact_email" :value="__('Contact Email')" />
              <x-text-input id="contact_email" class="block mt-1 w-full" type="text" name="contact_email" :value="old('contact_email', $enterprise->contact_email)" required autocomplete="contact_name" />
              <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
            </div>

            <!-- Contact Phone -->
            <div class="mt-4">
              <x-input-label for="contact_phone" :value="__('Contact Phone')" />
              <x-text-input id="contact_phone" class="block mt-1 w-full" type="tel" name="contact_phone" :value="old('contact_phone', $enterprise->contact_phone)" required autocomplete="contact_phone" />
              <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
            </div>

            <!-- Legal ID -->
            <div class="mt-4">
              <x-input-label for="legal_id" :value="__('Legal ID')" />
              <x-text-input id="legal_id" class="block mt-1 w-full" type="text" name="legal_id" :value="old('contact_phone', $enterprise->legal_id)" required autocomplete="legal_id" />
              <x-input-error :messages="$errors->get('legal_id')" class="mt-2" />
            </div>

            <!-- Legal Name -->
            <div class="mt-4">
              <x-input-label for="legal_name" :value="__('Legal Name')" />
              <x-text-input id="legal_name" class="block mt-1 w-full" type="text" name="legal_name" :value="old('contact_phone', $enterprise->legal_name)" required autocomplete="legal_name" />
              <x-input-error :messages="$errors->get('legal_name')" class="mt-2" />
              <x-input-error :messages="$errors->get('error')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <a class="ms-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('enterprises.index') }}">
                {{ __('Return') }}
              </a>

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
