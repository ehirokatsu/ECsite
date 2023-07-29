<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
            
        </div>
        
    </form>
    <br>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="flex justify-center items-center">
        <x-text-input id="email" class="" type="hidden" name="email" value="test@test.com"/>
        <x-text-input id="password" class="" type="hidden" name="password" value="testtest"/>
        <p class="border-spacing-2">テストアカウントでログインします。</p>
        <x-primary-button class="ml-3">
          {{ __('簡単ログイン') }}
        </x-primary-button>
      </div>
    </form>

    <!--カート画面からリダイレクトされてきた時だけ購入ボタンを表示する-->
    @if (strpos(Request::header('referer'), 'cart') !== false)
      <a href="{{ route('cart.buyer') }}">ログインせずに購入する</a>
    <form method="POST" action="{{ route('cart.register') }}">
      @csrf
      <button type="submit">登録</button><!--追加-->
    </form>
    @endif
</x-guest-layout>
