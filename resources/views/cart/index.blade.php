<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">            
              @if (!empty($carts))
                <div class="flex">
                  <x-primary-button class="m-2">
                    <a href="{{ route('cart.regConfirm') }}">購入する</a>
                  </x-primary-button>
                  <div class="m-2">
                    <form action="{{ route('cart.allDelete') }}" method="post">
                      @method('DELETE')
                      @csrf
                      <x-danger-button type="submit">
                        <span>カートを空にする</span>
                      </x-danger-button>
                    </form>
                  </div>
                </div>
                @foreach($carts as $cart)
                  <div class="flex p-2 rounded-md justify-center">
                    <div class="">
                      <img src="/storage/{{$cart['product']->image}}"  class="w-40 h-auto">
                    </div>
                    <div class="mr-20">
                      <div class="p-4">
                        <h2 class="text-lg font-semibold mb-2">{{ $cart['product']->name }}</h2>
                        <p class="text-gray-600 mb-2">¥{{ $cart['product']->cost }}</p>
                      </div>
                    </div>
                    <div class="">
                      <div class="flex p-3">
                        <form action="{{ route('cart.quantityUpdate', ['id' => $cart['product']['id']]) }}" method="post">
                          @method('PUT')
                          @csrf
                            <label for="">個数</label>
                            <input type="number" name="quantity" class="w-20" value="{{ $cart['quantity'] }}">
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            <x-secondary-button type="submit" class=" m-2">
                              更新する
                            </x-secondary-button>
                        </form>
                        <form action="{{ route('cart.destroy', ['id' => $cart['product']['id']]) }}" method="post">
                          @method('DELETE')
                          @csrf
                          <x-danger-button type="submit" class="m-2">
                            削除する
                          </x-danger-button>
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <span>カートは空です。</span>
              @endif
          </div>
      </div>
  </div>
</x-app-layout>