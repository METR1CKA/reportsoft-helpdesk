<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Enterprises') }}
    </h2>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="flex justify-between" style="margin-bottom: 25px;">
            <a href="{{ route('enterprises.create') }}">
              <x-primary-button>
                <i class="fas fa-user-plus"> </i>
                {{ __('Create New Enterprise') }}
              </x-primary-button>
            </a>
            <form method="get" action="{{ route('enterprises.show') }}" class="flex">
              @csrf
              @method('get')

              <x-auth-session-status :status="session('status')" />
              <x-input-error :messages="$errors->get('error')" />
              <x-text-input
                id="search"
                placeholder="Buscar empresa"
                class="block"
                type="text" name="search"
                :value="old('search')"
                required autofocus autocomplete="search"
                style="margin-right: 10px; margin-left: 10px;"
                />
              <x-primary-button style="margin-right: 10px;">
                <i class="fas fa-search"></i>
                {{ __('Search Enterprise') }}
              </x-primary-button>
            </form>
          </div>
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Contact Name</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Contact Phone</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Contact Email</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Legal ID</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Legal Name</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Status</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Edit</th>
                @can('is-admin')
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Delete</th>
                @endcan
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              @foreach ($enterprises as $enterprise)
              <tr>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $enterprise->contact_name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $enterprise->contact_phone }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $enterprise->contact_email }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $enterprise->legal_id }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $enterprise->legal_name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $enterprise->active ? 'Active' : 'Inactive' }}</td>
                <td class="px-3 py-4 whitespace-nowrap">
                  <form method="get" action="{{ route('enterprises.update', $enterprise->id) }}">
                    @csrf
                    @method('get')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-0 focus:outline-none">
                      <i class="fas fa-edit"></i>
                    </button>
                  </form>
                </td>
                @can('is-admin')
                <td class="px-3 py-4 whitespace-nowrap">
                  <form method="post" action="{{ route('enterprises.delete', $enterprise->id) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-0 focus:outline-none">
                      @if($enterprise->active)
                        <i class="fas fa-toggle-on"></i>
                      @else
                        <i class="fas fa-toggle-off"></i>
                      @endif
                    </button>
                  </form>
                </td>
                @endcan
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
