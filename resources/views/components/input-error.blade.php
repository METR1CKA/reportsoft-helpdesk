@props(['messages'])

@if ($messages)
<ul
    x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 10000)"
    {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}
    >
  @foreach ((array) $messages as $message)
  <li>{{ $message }}</li>
  @endforeach
</ul>
@endif
