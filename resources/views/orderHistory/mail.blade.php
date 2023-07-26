<h1>注文内容を受け付けました。</h1>
<div class="p-4">
  <h2>注文者情報</h2>
</div>
<div class="p-4">
  <span>名前 :</span>
  {{$userInfos['name']}}
</div>
<div class="p-4">
  <span>メールアドレス :</span>
  {{$userInfos['email']}}
</div>
<div class="p-4">
  <span>住所:</span>
  {{$userInfos['postalCode']}}
  {{$userInfos['address1']}}
  {{$userInfos['address2']}}
  {{$userInfos['address3']}}
</div>
<div class="p-4">
  <span>電話番号 :</span>
  {{$userInfos['phoneNumber']}}
</div>
<div class="p-4">
  <span>合計金額 :</span>
  {{$totalAmount}}
</div>
<br>
@foreach($carts as $cart)
<div class="flex p-2 border rounded-md justify-center">

  <div class="">
    <div class="flex border-b">
      <div class="p-4">
        <span>商品ID :</span>
        {{$cart['product']->id}}
      </div>
      <div class="p-4 text-lg font-semibold text-slate-500">
        <span>商品名 :</span>
        {{$cart['product']->name}}
      </div>
    </div>
    <div class="flex border-b">
      <div class="p-4">
        <span>単価 :</span>
        {{$cart['product']->cost}}
      </div>
      <div class="p-4 text-lg font-semibold text-slate-500">
        <span>個数 :</span>
        {{$cart['quantity']}}
      </div>
    </div>
  </div>
</div>
@endforeach