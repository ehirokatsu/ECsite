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
                @foreach($carts as $cart)
                  <p>{{ $cart['product']->id }}</p>
                  <p>{{ $cart['product']->name }}</p>
                  <form action="{{ route('cart.quantityUpdate', ['id' => $cart['product']['id']]) }}" method="post">
                    @method('PUT')
                    @csrf
                    <label for="">個数</label>
                    <input type="text" name="quantity" value="{{ $cart['quantity'] }}">
                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    <input type="submit" value="更新">
                  </form>
                  <img class="" src="/storage/{{$cart['product']->image}}">
                  <form action="{{ route('cart.destroy', ['id' => $cart['product']['id']]) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <input type="submit" value="削除">
                  </form>
                @endforeach
                <a href="{{ route('cart.regConfirm') }}">購入する</a>
                <form action="{{ route('cart.allDelete') }}" method="post">
                  @method('DELETE')
                  @csrf
                  <input type="submit" value="全削除">
                </form>
              @else
                カートは空です。
              @endif
          </div>
      </div>
  </div>
</x-app-layout>