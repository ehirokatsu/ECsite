<div class="flex justify-between">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      MyECサイト
  </h2>
  @if(isset(Auth::user()->name))
    <h2>ようこそ {{ Auth::user()->name }} さま</h2>
  @else
    <h2>ようこそ ゲスト さま</h2>
  @endif
</div>
<div class="flex justify-between">
  <div class="flex">
    <x-a-href href="/" class="">トップ</x-a-href>
    <x-a-href href="/cart" class="" >カート</x-a-href>
    <x-a-href href="/contact" class="" >問合せ</x-a-href>
    @if(isset(Auth::user()->name))
      <x-a-href href="{{ route('orderHistory') }}" class="" >注文履歴</x-a-href>
      <x-a-href href="/profile" class="" >アカウント編集</x-a-href>
    @endif
    @can('admin')
      <x-a-href href="/create" class="" >商品追加</x-a-href>
      <x-a-href href="/databaseManage" class="" >DBメンテ</x-a-href>
    @endcan
    <x-a-href href="{{ route('htmlTest') }}" class="" >HTMLテスト</x-a-href>

    <x-a-href href="{{ route('vueTest') }}" class="" >VUEテスト</x-a-href>
    <x-a-href href="{{ route('vue.index') }}" class="" >VUE TOP</x-a-href>
    <x-a-href href="{{ route('vueThree') }}" class="" >VUE ○×</x-a-href>
    <x-a-href href="{{ route('vueSnake') }}" class="" >VUE Snake</x-a-href>
    <x-a-href href="{{ route('vueSlot') }}" class="" >VUE Slot</x-a-href>
  </div>
  <div class="flex justify-end">
    @if(isset(Auth::user()->name))
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="submit" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded"  value="ログアウト">
      </form>
    @else
    <x-a-href href="{{ route('register') }}" class="" >登録</x-a-href>
      <x-a-href href="{{ route('login') }}" class="" >ログイン</x-a-href>
    @endif
  </div>
</div>
