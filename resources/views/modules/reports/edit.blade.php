<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Edit Report') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form method="post" action="{{ route('reports.update', $report->id) }}">
            @csrf
            @method('put')

            <!-- Name -->
            <div class="mt-4">
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $report->name)" required autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mt-4">
              <x-input-label for="description" :value="__('Description')" />
              <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description', $report->description)" required autofocus autocomplete="description" />
              <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Comments -->
            <div class="mt-4">
              <x-input-label for="comments" :value="__('Comments')" />
              <x-text-input id="comments" class="block mt-1 w-full" type="text" name="comments" :value="old('comments', $report->comments)" required autofocus autocomplete="comments" />
              <x-input-error :messages="$errors->get('comments')" class="mt-2" />
            </div>

            <!-- User -->
            <div class="mt-4">
              <x-input-label for="user_id" :value="__('User')" />
              <select id="user_id" name="user_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled>Select user</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ $user->id == $report->user_id ? 'selected' : ''}}>
                    {{ $user->username }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
            </div>

            <!-- Area -->
            <div class="mt-4">
              <x-input-label for="area_id" :value="__('Area')" />
              <select id="area_id" name="area_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled>Select area</option>
                @foreach ($areas as $area)
                  <option value="{{ $area->id }}" {{ $area->id == $report->area_id ? 'selected' : ''}}>
                    {{ $area->name }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('area_id')" class="mt-2" />
            </div>

            <!-- Enterprise -->
            <div class="mt-4">
              <x-input-label for="enterprise_id" :value="__('Enterprise')" />
              <select id="enterprise_id" name="enterprise_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled>Select enterprise</option>
                @foreach ($enterprises as $enterprise)
                  <option value="{{ $enterprise->id }}" {{ $enterprise->id == $report->enterprise_id ? 'selected' : ''}}>
                    {{ $enterprise->legal_name }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('enterprise_id')" class="mt-2" />
            </div>

            <!-- Project -->
            <div class="mt-4">
              <x-input-label for="project_id" :value="__('Project')" />
              <select id="project_id" name="project_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled>Select project</option>
                @foreach ($projects as $project)
                  <option value="{{ $project->id }}" {{ $project->id == $report->project_id ? 'selected' : ''}}>
                    {{ $project->name }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
            </div>

            <!-- Report Status -->
            <div class="mt-4">
              <x-input-label for="report_status_id" :value="__('Report Status')" />
              <select id="report_status_id" name="report_status_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled>Select report status</option>
                @foreach ($report_statuses as $report_status)
                  <option value="{{ $report_status->id }}" {{ $report_status->id == $report->report_status_id ? 'selected' : ''}}>
                    {{ $report_status->name }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('report_status_id')" class="mt-2" />
              <x-input-error :messages="$errors->get('error')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <a class="ms-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('reports.index') }}">
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
