<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
              </div>
             
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
                  <div class="flex p-2 border rounded-md justify-center">
                    <div class="">
                      <img class="max-h-60 max-w-60" src="/storage/{{$cart['product']->image}}">
                    </div>
                    <div class="">
                      <div class="flex border-b">
                        <div class="p-4">
                          <span>商品名 :</span>
                          {{ $cart['product']->name }}
                        </div>
                        <div class="p-4 text-lg font-semibold text-slate-500">
                          <span>単価 : </span>
                          {{ $cart['product']->cost }}
                        </div>
                      </div>
                      <div class="">
                        <div class="p-3">
                          <form action="{{ route('cart.quantityUpdate', ['id' => $cart['product']['id']]) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div>
                              <label for="">個数</label>
                              <input type="text" name="quantity" value="{{ $cart['quantity'] }}">
                              <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>
                            <div class="m-4">
                              <x-secondary-button type="submit" class="">
                                更新する
                              </x-secondary-button>
                            </div>
                          </form>
                          <form action="{{ route('cart.destroy', ['id' => $cart['product']['id']]) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <x-danger-button type="submit" class="m-4">
                              削除する
                            </x-danger-button>
                          </form>
                        </div>
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