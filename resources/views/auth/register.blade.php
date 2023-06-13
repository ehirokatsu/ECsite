<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
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

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="postalCode" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- 郵便番号 -->
        <div class="mt-4">
          <x-input-label for="postalCode" :value="__('郵便番号')" />

          <x-text-input id="postalCode" class="block mt-1 w-full"
                          type="text"
                          name="postalCode" required autocomplete="postalCode" />

          <x-input-error :messages="$errors->get('postalCode')" class="mt-2" />
        </div>
        <!-- 住所１ -->
        <div class="mt-4">
          <x-input-label for="address_1" :value="__('都道府県')" />

          <x-text-input id="address_1" class="block mt-1 w-full"
                          type="text"
                          name="address_1" required autocomplete="address_1" />

          <x-input-error :messages="$errors->get('address_1')" class="mt-2" />
        </div>
        <!-- 住所２ -->
        <div class="mt-4">
          <x-input-label for="address_2" :value="__('市区町村')" />

          <x-text-input id="address_2" class="block mt-1 w-full"
                          type="text"
                          name="address_2" required autocomplete="address_2" />

          <x-input-error :messages="$errors->get('address_2')" class="mt-2" />
        </div>
        <!-- 住所３ -->
        <div class="mt-4">
          <x-input-label for="address_3" :value="__('番地以降')" />

          <x-text-input id="address_3" class="block mt-1 w-full"
                          type="text"
                          name="address_3" required autocomplete="address_3" />

          <x-input-error :messages="$errors->get('address_3')" class="mt-2" />
        </div>
        <!-- 電話番号 -->
        <div class="mt-4">
          <x-input-label for="phoneNumber" :value="__('電話番号')" />

          <x-text-input id="phoneNumber" class="block mt-1 w-full"
                          type="text"
                          name="phoneNumber" required autocomplete="phoneNumber" />

          <x-input-error :messages="$errors->get('phoneNumber')" class="mt-2" />
        </div>
        
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
