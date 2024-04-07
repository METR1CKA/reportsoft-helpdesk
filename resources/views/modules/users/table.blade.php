<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Users') }}
    </h2>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="flex justify-between" style="margin-bottom: 25px;">
            <a href="{{ route('users.create') }}">
              <x-primary-button>
                <i class="fas fa-user-plus"> </i>
                {{ __('Create New User') }}
              </x-primary-button>
            </a>
            <form method="get" action="{{ route('users.show') }}" class="flex">
              @csrf
              @method('get')

              <x-auth-session-status :status="session('status')" />
              <x-input-error :messages="$errors->get('error')" />
              <x-text-input
                id="search"
                placeholder="Buscar usuario"
                class="block"
                type="text" name="search"
                :value="old('search')"
                required autofocus autocomplete="search"
                style="margin-right: 10px; margin-left: 10px;"
                />
              <x-primary-button style="margin-right: 10px;">
                <i class="fas fa-search"></i>
                {{ __('Search User') }}
              </x-primary-button>
            </form>
          </div>
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Username</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Edit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Delete</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              @foreach ($users as $user)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $user->username }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $user->role()->first()->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $user->active ? 'Active' : 'Inactive' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <form method="get" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('get')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-0 focus:outline-none">
                      <i class="fas fa-edit"></i>
                    </button>
                  </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <form method="post" action="{{ route('users.delete', $user->id) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-0 focus:outline-none">
                      @if($user->active)
                        <i class="fas fa-toggle-on"></i>
                      @else
                        <i class="fas fa-toggle-off"></i>
                      @endif
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <x-input-error class="mt-2" :messages="$errors->get('error')" />
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
