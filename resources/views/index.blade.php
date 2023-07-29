<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($products as $product)
              <div class="border border-gray-300 p-4 rounded-md">
                <a href="{{ route('show', ['id' => $product->id]) }}" class="" >
                  <img alt="商品画像" class="w-50 h-50 mb-2 rounded-md" src="/storage/{{$product->image}}">
                </a>
                <div class="flex justify-between">
                  <div>
                    <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-600 mb-2">¥{{ $product->cost }}</p>
                  </div>
                  <div class="">
                    <form action="{{ route('cart.store') }}" method="post">
                      @csrf
                      <input type="hidden" name="id" value="{{ $product->id }}">
                      <x-primary-button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-2 py-2 rounded">>
                        カートに入れる
                      </x-primary-button>
                    </form>
                    @can('admin')
                    <div class="flex p-2">
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
              </div>
            @endforeach
            </div>
          </div>
      </div>
  </div>
</x-app-layout>