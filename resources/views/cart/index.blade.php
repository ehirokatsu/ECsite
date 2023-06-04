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
                  <img class="" src="/storage/{{$cart['product']->image}}">
                  <form action="{{ route('cart.destroy', ['id' => $cart['product']['id']]) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <input type="submit" value="削除">
                  </form>
                @endforeach
                <form action="" method="post">
                  @csrf
                  <button type="submit" class="">
                    購入する
                  </button>
                </form>
              @else
                カートは空です。
              @endif
          </div>
      </div>
  </div>
</x-app-layout>