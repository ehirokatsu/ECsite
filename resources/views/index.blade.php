<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            @foreach($products as $product)
            <div class="flex p-2 border rounded-md justify-center">
              <div class="">
                <img class="max-h-60 max-w-60" src="/storage/{{$product->image}}">
              </div>
              <div class="">
                <div class="flex border-b">
                  <div class="p-4">
                    商品名 : {{ $product->name }}
                  </div>
                  <div class="p-4 text-lg font-semibold text-slate-500">
                    単価 : {{ $product->cost }}
                  </div>
                </div>
                <div class="p-4 text-sm font-medium text-slate-700 mt-2">
                  <a href="{{ route('show', ['id' => $product->id]) }}" class="" >
                    詳細
                  </a>
                </div>
                <div class="p-3">
                  <form action="{{ route('cart.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <x-primary-button type="submit" class="">
                      カートに入れる
                    </x-primary-button>
                  </form>
                </div>
                @can('admin')
                <div class="flex p-3">
                  <div class="p-1">
                    <a href="{{ route('edit', ['id' => $product->id]) }}" class="" >
                      <x-primary-button>
                        編集
                      </x-primary-button>
                    </a>
                  </div>
                  <div class="p-1">
                    <form action="{{ route('destroy', ['id' => $product->id]) }}" method="post">
                      @method('DELETE')
                      @csrf
                      <x-danger-button type="submit" class="">
                        削除
                      </x-danger-button>
                    </form>
                  </div>
                </div>
                @endcan
              </div>
            </div>
          @endforeach
          </div>
      </div>
  </div>
</x-app-layout>