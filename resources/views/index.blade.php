<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ECsite
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <a href="/" class="" >カート</a>
                <a href="/" class="" >履歴</a>
                <a href="/" class="" >アカウント編集</a>
                <a href="/" class="" >問合せ</a>
                <a href="/create" class="" >商品追加</a>
                <a href="/" class="" >DBメンテ</a>
              </div>
              @foreach($products as $product)
                商品名{{ $product->name }}
                単価{{ $product->cost }}
                <img class="" src="/storage/{{$product->image}}">
                <a href="/{{ $product->id }}" class="" >
                  詳細
                </a>
                <form action="/" method="post">
                  @csrf
                  <button type="submit" class="">
                    カートに入れる
                  </button>
                </form>
                <a href="/{{ $product->id }}/edit" class="" >
                  編集
                </a>
                <form action="/{{ $product->id }}" method="post">
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