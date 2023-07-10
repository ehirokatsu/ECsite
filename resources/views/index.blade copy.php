<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

              </div>
              <h1 class="text-xl text-green-100">Hello World</h1>

              @foreach($products as $product)
              <div class="grid grid-cols-12">
                <div class="col-span-3">
                </div>
                <div class="col-span-3">
                  <img class="max-h-40" src="/storage/{{$product->image}}">
                </div>
                <div class="col-span-3">
                  <div class="grid grid-cols-1 gap-2">
                    <div class="col-span-1">
                      商品名 : {{ $product->name }}
                    </div>
                    <div class="col-span-1">
                      単価 : {{ $product->cost }}
                    </div>
                    <div class="col-span-1">
                      <a href="{{ route('show', ['id' => $product->id]) }}" class="" >
                        詳細
                      </a>
                    </div>
                    <div class="col-span-1">
                      <form action="{{ route('cart.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <x-primary-button type="submit" class="">
                          カートに入れる
                        </x-primary-button>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-span-3">
                </div>

                <div class="col-span-3">
                </div>
                @can('admin')
                <div class="col-span-3">
                  <a href="{{ route('edit', ['id' => $product->id]) }}" class="" >
                    <x-primary-button>
                      編集
                    </x-primary-button>
                  </a>
                </div>
                <div class="col-span-3">
                  <form action="{{ route('destroy', ['id' => $product->id]) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <x-danger-button type="submit" class="">
                      削除
                    </x-danger-button>
                  </form>
                </div>
                @endcan
                <div class="col-span-3">
                </div>
              </div>
              <br>
              @endforeach
          </div>
      </div>
  </div>
</x-app-layout>