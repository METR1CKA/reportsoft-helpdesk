<x-guest-layout>
  <form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- UserName -->
    <div>
      <x-input-label for="username" :value="__('Username')" />
      <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
      <x-input-error :messages="$errors->get('username')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
      <x-input-label for="password" :value="__('Password')" />
      <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
      <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
      <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <!-- Phone -->
    <div class="mt-4">
      <x-input-label for="phone" :value="__('Enter phone with country code (+52, +1, etc)')" />
      <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" required autocomplete="phone" />
      <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

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
      <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
        {{ __('Already registered?') }}
      </a>

      <x-primary-button class="ms-4">
        {{ __('Register') }}
      </x-primary-button>
    </div>
  </form>
</x-guest-layout>
