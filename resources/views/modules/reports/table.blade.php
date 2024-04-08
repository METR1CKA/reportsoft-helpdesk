<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Reports') }}
    </h2>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  </x-slot>

  <div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-9 overflow-x-auto">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="flex justify-between mb-6" >
            <a href="{{ route('reports.create') }}">
              <x-primary-button>
                <i class="fas fa-user-plus"> </i>
                {{ __('Create New Report') }}
              </x-primary-button>
            </a>
            <form method="get" action="{{ route('reports.show') }}" class="flex">
              @csrf
              @method('get')

              <x-auth-session-status :status="session('status')" />
              <x-input-error :messages="$errors->get('error')" />
              <x-text-input
                id="search"
                placeholder="Buscar empresa"
                class="block mr-2 ml-2"
                type="text" name="search"
                :value="old('search')"
                required autofocus autocomplete="search"
                style="margin-right: 10px; margin-left: 10px;"
                />
              <x-primary-button style="margin-right: 10px;">
                <i class="fas fa-search"></i>
                {{ __('Search Report') }}
              </x-primary-button>
            </form>
          </div>
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">User</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Area</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Enterprise</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Project</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Section</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Name</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Description</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Comments</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Status</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Edit</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Delete</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              @foreach ($reports as $report)
              <tr>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->user()->first()->username }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->area()->first()->name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->enterprise()->first()->legal_name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->project()->first()->name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->reportStatus()->first()->name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->name }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->description }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->comments }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $report->active ? 'Active' : 'Inactive' }}</td>
                <td class="px-3 py-4 whitespace-nowrap">
                  <form method="get" action="{{ route('reports.update', $report->id) }}">
                    @csrf
                    @method('get')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-0 focus:outline-none">
                      <i class="fas fa-edit"></i>
                    </button>
                  </form>
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                  <form method="post" action="{{ route('reports.delete', $report->id) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium border-0 focus:outline-none">
                      @if($report->active)
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
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
