<x-guest-layout>
  <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Hello, please enter the Auth-factor authentication code you received via EMAIL.') }}
  </div>

  @if (session('status') == 'verification-link-sent')
    <div
      x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
      class="mb-4 font-medium text-sm text-green-600 dark:text-green-400"
      >
      {{ __('A new auth factor code verification link has been sent through the email address you provided.') }}
    </div>
  @endif

  @if (session('status') == 'verification-link-sent-error')
    <div
      x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
      class="mb-4 font-medium text-sm text-red-600 dark:text-red-400"
      >
      {{ __('There has been an error when sending the auth factor verification code.') }}
      @if (session()->has('mail-error'))
        {{ session('mail-error') }}
      @endif
    </div>
  @endif

  <form method="POST" action="{{ route('auth-factor.verify-code') }}">
    @csrf

    <!-- Code -->
    <div>
      <x-input-label for="code" :value="__('Enter the code')" />

      <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" required autocomplete="code" />

      <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <!-- Recaptcha V2 -->
    <div class="form-group mt-3">
      {!! NoCaptcha::renderJs() !!}
      {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
    </div>

    @if ($errors->has('g-recaptcha-response'))
    <div
      x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
      class="form-group mt-3"
      >
      <span class="help-block">
        <strong class="text-red-500">{{ $errors->first('g-recaptcha-response') }}</strong>
      </span>
    </div>
    @endif

    <div class="flex items-center justify-end mt-4">
      <a class="ms-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('auth-factor.resend-code') }}">
        {{ __('Resend Auth-FA Code') }}
      </a>

      <x-primary-button class="ms-4">
        {{ __('Send Verification Code') }}
      </x-primary-button>
    </div>
  </form>
</x-guest-layout>
