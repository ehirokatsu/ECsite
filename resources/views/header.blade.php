<div class="flex justify-between">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      MyECサイト
  </h2>
  @if(isset(Auth::user()->name))
    <h2>ようこそ {{ Auth::user()->name }} さま</h2>
  @else
    <h2>ようこそ ゲスト さま</h2>
  @endif<!--追加-->
</div>
<div class="flex justify-between">
  <div class="flex">
    <a href="/" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded">トップ</a>
    <a href="/cart" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >カート</a>
    <a href="{{ route('orderHistory') }}" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >注文履歴</a>
    <a href="/profile" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >アカウント編集</a>
    <a href="/contact" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >問合せ</a>
    @can('admin')
    <a href="/create" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >商品追加</a>
    <a href="/databaseManage" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >DBメンテ</a>
    @endcan
    <a href="{{ route('register') }}" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >商品登録</a>
    <a href="{{ route('htmlTest') }}" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >HTMLテスト</a>
  </div>
  <div class="flex justify-end">
    <a href="{{ route('login') }}" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded" >ログイン</a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <input type="submit" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-black hover:font-bold rounded"  value="ログアウト">
    </form>
  </div>
</div>
