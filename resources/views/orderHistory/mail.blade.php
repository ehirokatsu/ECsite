お問い合わせ内容を受け付けました。
<div class="p-4">
  <span>注文ID :</span>
  {{$userInfos['name']}}
</div>

@foreach($carts as $cart)
<div class="flex p-2 border rounded-md justify-center">
  <div class="h-60 w-60">
    <img class="max-h-60 max-w-60" src="/storage/{{$cart['product']->image}}">
  </div>
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