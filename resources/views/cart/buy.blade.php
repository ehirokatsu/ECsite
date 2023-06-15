<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
            </div>
            購入者情報を入力してください。
            <form method="post" action="{{ route('cart.buyConfirm') }}">
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

              <!-- 郵便番号 -->
              <div class="mt-4">
              <x-input-label for="postalCode" :value="__('Postal Code')" />

              <x-text-input id="postalCode" class="block mt-1 w-full"
                              type="text"
                              name="postalCode" required autocomplete="postalCode" />

              <x-input-error :messages="$errors->get('postalCode')" class="mt-2" />
              </div>
              <!-- 住所１ -->
              <div class="mt-4">
              <x-input-label for="address1" :value="__('Prefecture')" />

              <x-text-input id="address1" class="block mt-1 w-full"
                              type="text"
                              name="address1" required autocomplete="address1" />

              <x-input-error :messages="$errors->get('address1')" class="mt-2" />
              </div>
              <!-- 住所２ -->
              <div class="mt-4">
              <x-input-label for="address2" :value="__('City')" />

              <x-text-input id="address2" class="block mt-1 w-full"
                              type="text"
                              name="address2" required autocomplete="address2" />

              <x-input-error :messages="$errors->get('address2')" class="mt-2" />
              </div>
              <!-- 住所３ -->
              <div class="mt-4">
              <x-input-label for="address3" :value="__('Other Address')" />

              <x-text-input id="address3" class="block mt-1 w-full"
                              type="text"
                              name="address3" required autocomplete="address3" />

              <x-input-error :messages="$errors->get('address3')" class="mt-2" />
              </div>
              <!-- 電話番号 -->
              <div class="mt-4">
              <x-input-label for="phoneNumber" :value="__('Phone Number')" />

              <x-text-input id="phoneNumber" class="block mt-1 w-full"
                              type="text"
                              name="phoneNumber" required autocomplete="phoneNumber" />

              <x-input-error :messages="$errors->get('phoneNumber')" class="mt-2" />
              </div>
              <button type="submit">入力内容確認</button>
            </form>
          </div>
      </div>
  </div>
</x-app-layout>