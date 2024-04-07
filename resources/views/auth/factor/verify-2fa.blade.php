<x-guest-layout>
  <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Hello, please enter the 2FA authentication code you received via EMAIL.') }}
  </div>

  <form method="POST" action="{{ route('2FA.verify-code') }}">
    @csrf

    <!-- Code -->
    <div>
      <x-input-label for="code" :value="__('Enter the code')" />

      <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" required autocomplete="code" />

      <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    @if (session('status'))
    <div class="alert alert-success text-green-500">
      {{ session('status') }}
    </div>
    @endif

    <!-- Recaptcha V2 -->
    <div class="form-group mt-3">
      {!! NoCaptcha::renderJs() !!}
      {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
    </div>

    @if ($errors->has('g-recaptcha-response'))
    <div class="form-group mt-3">
      <span class="help-block">
        <strong class="text-red-500">{{ $errors->first('g-recaptcha-response') }}</strong>
      </span>
    </div>
    @endif

    <div class="flex items-center justify-end mt-4">
      <x-primary-button class="ms-4">
        {{ __('Send Verification Code') }}
      </x-primary-button>
    </div>
  </form>
</x-guest-layout>
