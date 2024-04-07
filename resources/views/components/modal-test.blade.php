<div id="registerModal" class="modal bg-white dark:bg-gray-900" style="display: none;position: fixed;z-index: 1;left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4); ">
    <div class="modal-content bg-white dark:bg-gray-800" style="margin: 15% auto;padding: 20px;border: 1px solid #888;width: 80%;">
      <span class="close" style="color: #aaa;float: right;font-size: 28px;font-weight: bold;"
      onmouseover="this.style.color=black"
      onmouseout="this.style.color='#aaa'"
       >&times;</span>
      <h2 class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Register</h2>
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
    <!-- <div class="form-group mt-3">
      {!! NoCaptcha::renderJs() !!}
      {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
    </div> -->

    @if ($errors->has('g-recaptcha-response'))
    <div class="form-group mt-3">
      <span class="help-block">
        <strong class="text-red-500">{{ $errors->first('g-recaptcha-response') }}</strong>
      </span>
    </div>
    @endif

    <div class="flex items-center justify-end mt-4">
      <x-primary-button class="ms-4">
        {{ __('Register') }}
      </x-primary-button>
    </div>
  </form>
    </div>
</div>


<script>
    // Get the modal
    var modal = document.getElementById("registerModal");

    // Get the button that opens the modal
    var btn = document.getElementById("registerLink");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
</script>
