<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
            </div>
            購入者情報は以下でよろしいですか？
            <form method="post" action="{{ route('cart.regComplete') }}">
              @csrf
              <!-- Name -->
              <div>
                <x-input-label for="name" :value="__('Name')" />
                {{ Auth::user()->name }}
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
              </div>

              <!-- Email Address -->
              <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                {{ Auth::user()->email }}
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
              </div>

              <!-- 郵便番号 -->
              <div class="mt-4">
                <x-input-label for="postalCode" :value="__('Postal Code')" />
                {{ Auth::user()->postal_code }}
                <input type="hidden" name="postalCode" value="{{ Auth::user()->postal_code }}">
              </div>

              <!-- 住所１ -->
              <div class="mt-4">
                <x-input-label for="address1" :value="__('Prefecture')" />
                {{ Auth::user()->address_1 }}
                <input type="hidden" name="address1" value="{{ Auth::user()->address_1 }}">
              </div>

              <!-- 住所２ -->
              <div class="mt-4">
                <x-input-label for="address2" :value="__('City')" />
                {{ Auth::user()->address_2 }}
                <input type="hidden" name="address2" value="{{ Auth::user()->address_2 }}">
              </div>

              <!-- 住所３ -->
              <div class="mt-4">
                <x-input-label for="address3" :value="__('Other Address')" />
                {{ Auth::user()->address_3 }}
                <input type="hidden" name="address3" value="{{ Auth::user()->address_3 }}">
              </div>

              <!-- 電話番号 -->
              <div class="mt-4">
                <x-input-label for="phoneNumber" :value="__('Phone Number')" />
                {{ Auth::user()->phone_number }}
                <input type="hidden" name="phoneNumber" value="{{ Auth::user()->phone_number }}">
              </div>

              <br>
              <button type="submit" name="action" value="submit">注文を確定する</button>


            </form>
            @if ($errors->any())
            <div class="">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
            @endif
          </div>
      </div>
  </div>
</x-app-layout>