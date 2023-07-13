<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

              </div>
              <h1 class="text-xl text-green-10">Hello World</h1>
              <div class="flex w-130 border">aaaa</div>
              @foreach($products as $product)

              <div class="flex">
                <div class="flex-none">
                  <img class="max-h-40" src="/storage/{{$product->image}}">
                </div>
                <div class="flex-auto border">
                  <div class="">
                    <div class="border">
                      商品名 : {{ $product->name }}
                    </div>
                    <div class="border">
                      単価 : {{ $product->cost }}
                    </div>
                    <div class="border">
                      <a href="{{ route('show', ['id' => $product->id]) }}" class="" >
                        詳細
                      </a>
                    </div>
                    <div class="border">
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

                        


                  @can('admin')
                    <a href="{{ route('edit', ['id' => $product->id]) }}" class="" >
                      <x-primary-button>
                        編集
                      </x-primary-button>
                    </a>
                    <form action="{{ route('destroy', ['id' => $product->id]) }}" method="post">
                      @method('DELETE')
                      @csrf
                      <x-danger-button type="submit" class="">
                        削除
                      </x-danger-button>
                    </form>

                  @endcan
              </div>
              <br>
              @endforeach
          </div>
      </div>
  </div>
</x-app-layout>