<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ECsite
      </h2>
      <a href="/" class="" >カート</a>
      <a href="/" class="" >履歴</a>
      <a href="/" class="" >アカウント編集</a>
      <a href="/" class="" >問合せ</a>
      <a href="/create" class="" >商品追加</a>
      <a href="/" class="" >DBメンテ</a>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <a href="/" class="" >カートの中身</a>
              </div>
              @if (!empty($carts))
                @foreach($carts as $cart)
                  <p>{{ $cart['id'] }}</p>
                  <form action="{{ route('cart.destroy', ['id' => $cart['id']]) }}" method="post">
                    @csrf
                    <input type="submit" value="削除">
                  </form>
                @endforeach
              @endif
          </div>
      </div>
  </div>
</x-app-layout>