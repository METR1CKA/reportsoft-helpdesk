<x-guest-layout>
  <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Thank you for validating your email! Before we begin, could you verify your authentication code on your email address by clicking the link we just sent you via email? If you did not receive the email, we will be happy to send you another one.') }}
  </div>

  @if (session('status') == 'verification-link-sent')
  <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
    {{ __('A new 2FA code verification link has been sent through the email address you provided.') }}
  </div>
  @endif

  @if (session('status') == 'verification-link-sent-error')
  <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
    {{ __('There has been an error when sending the 2FA verification code') }}
  </div>
  @endif

  <div class="mt-4 flex items-center justify-between">
    <form method="POST" action="{{ route('2FA.send-code') }}">
      @csrf

      <div>
        <x-primary-button>
          {{ __('Send 2FA Code Verification') }}
        </x-primary-button>
      </div>
    </form>

    <form method="POST" action="{{ route('logout') }}">
      @csrf

      <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
        {{ __('Log Out') }}
      </button>
    </form>
  </div>
</x-guest-layout>
