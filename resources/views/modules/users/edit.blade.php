<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Edit User') }}
    </h2>
  </x-slot>

  <!-- Aquí puedes crear el formulario para editar un usuario existente -->
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form method="post" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('put')

            <!-- UserName -->
            <div>
              <x-input-label for="username" :value="__('Username')" />
              <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $user->username)" required autofocus autocomplete="username" value="{{ $user->username }}" />
              <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
              <x-input-label for="email" :value="__('Email')" />
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="email" />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
              <x-input-label for="phone" :value="__('Enter phone with country code (+52, +1, etc)')" />
              <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" required autocomplete="phone"
              value="{{ $user->phone }}" placeholder="{{ !$user->phone ? 'Without phone' : '' }}" />
              <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Role -->
            <div class="mt-4">
              <x-input-label for="role_id" :value="__('Select your role')" />
              <select id="role_id" name="role_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled>Select a role</option>
                @foreach ($roles as $name => $id)
                <option value="{{ $id }}" {{ $user->role->contains('id', $id) ? 'selected' : ''}}>
                  {{ $name }}
                </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
              <x-input-error :messages="$errors->get('error')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">

            <a class="ms-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('users.index') }}">
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
