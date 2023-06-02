<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

              </div>
              @foreach($products as $product)
                商品名{{ $product->name }}
                単価{{ $product->cost }}
                <img class="" src="/storage/{{$product->image}}">
                <a href="{{ route('show', ['id' => $product->id]) }}" class="" >
                  詳細
                </a>

                <form action="{{ route('cart.store') }}" method="post">
                  @csrf
                  <input type="hidden" name="id" value="{{ $product->id }}">
                  <button type="submit" class="">
                    カートに入れる
                  </button>
                </form>

                <a href="{{ route('edit', ['id' => $product->id]) }}" class="" >
                  編集
                </a>

                <form action="{{ route('destroy', ['id' => $product->id]) }}" method="post">
                  @method('DELETE')
                  @csrf
                  <button type="submit" class="">
                    削除
                  </button>
                </form>

              <br>
              @endforeach
          </div>
      </div>
  </div>
</x-app-layout>