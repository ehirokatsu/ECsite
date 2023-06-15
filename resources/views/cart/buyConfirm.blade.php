<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
              </div>
            購入者情報です。
            <form method="post" action="{{ route('cart.complete') }}">
              @csrf
              <!-- Name -->
              <div>
                <x-input-label for="name" :value="__('Name')" />
                {{ $inputs['name'] }}
                <input type="hidden" name="name" value="{{ $inputs['name'] }}">
              </div>

              <!-- Email Address -->
              <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                {{ $inputs['email'] }}
                <input type="hidden" name="email" value="{{ $inputs['email'] }}">
              </div>

              <!-- 郵便番号 -->
              <div class="mt-4">
                <x-input-label for="postalCode" :value="__('Postal Code')" />
                {{ $inputs['postalCode'] }}
                <input type="hidden" name="postalCode" value="{{ $inputs['postalCode'] }}">
              </div>

              <!-- 住所１ -->
              <div class="mt-4">
                <x-input-label for="address1" :value="__('Prefecture')" />
                {{ $inputs['address1'] }}
                <input type="hidden" name="address1" value="{{ $inputs['address1'] }}">
              </div>

              <!-- 住所２ -->
              <div class="mt-4">
                <x-input-label for="address2" :value="__('City')" />
                {{ $inputs['address2'] }}
                <input type="hidden" name="address2" value="{{ $inputs['address2'] }}">
              </div>

              <!-- 住所３ -->
              <div class="mt-4">
                <x-input-label for="address3" :value="__('Other Address')" />
                {{ $inputs['address3'] }}
                <input type="hidden" name="address3" value="{{ $inputs['address3'] }}">
              </div>

              <!-- 電話番号 -->
              <div class="mt-4">
                <x-input-label for="phoneNumber" :value="__('Phone Number')" />
                {{ $inputs['phoneNumber'] }}
                <input type="hidden" name="phoneNumber" value="{{ $inputs['phoneNumber'] }}">
              </div>

              <button type="submit" name="action" value="back">修正する</button>
              <button type="submit" name="action" value="submit">購入を確定する</button>
            </form>
          </div>
      </div>
  </div>
</x-app-layout>