<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    ECsite
</h2>
<a href="/" class="" >トップ</a>
<a href="/cart" class="" >カート</a>
<a href="/profile" class="" >アカウント編集</a>
<a href="/contact" class="" >問合せ</a>
@can('admin')
<a href="/create" class="" >商品追加</a>
<a href="/databaseManage" class="" >DBメンテ</a>
@endcan
<a href="{{ route('orderHistory') }}">注文履歴</a>
<a href="{{ route('register') }}">登録</a>
<a href="{{ route('login') }}">ログイン</a>
<a href="{{ route('htmlTest') }}">HTMLテスト</a>