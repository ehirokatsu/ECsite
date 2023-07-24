<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
              </div>
              <div class="flex">
                <div class="p-4">
                  <span>注文ID :</span>
                  {{$order->id}}
                </div>
                <div class="p-4">
                  <span>注文日 :</span>
                  {{$order->ordered_on}}
                </div>
              </div>
              <div class="flex">
                <div class="p-4">
                  <span>名前 :</span>
                  {{$order->name}}
                </div>
                <div class="p-4">
                  <span>メール :</span>
                  {{$order->email}}
                </div>
                <div class="p-4">
                  <span>住所 :</span>
                  {{$order->postal_code}}
                  {{$order->address_1}}
                  {{$order->address_2}}
                  {{$order->address_3}}
                </div>
                <div class="p-4">
                  <span>電話番号 :</span>
                  {{$order->phone_number}}
                </div>
              </div>
              @foreach($orderDetails as $orderDetail)
              <div class="flex p-2 border rounded-md justify-center">
                <div class="h-60 w-60">
                  <img class="max-h-60 max-w-60" src="/storage/{{$orderDetail->product->image}}">
                </div>
                <div class="">
                  <div class="flex border-b">
                    <div class="p-4">
                      <span>注文詳細ID :</span>
                      {{$orderDetail->id}}
                    </div>
                    <div class="p-4 text-lg font-semibold text-slate-500">
                      <span>商品名 :</span>
                      {{$orderDetail->name}}
                    </div>
                  </div>
                  <div class="flex border-b">
                    <div class="p-4">
                      <span>単価 :</span>
                      {{$orderDetail->cost}}
                    </div>
                    <div class="p-4 text-lg font-semibold text-slate-500">
                      <span>個数 :</span>
                      {{$orderDetail->quantity}}
                    </div>
                  </div>
                  <div class="flex border-b">
                    <div class="p-4">
                      <span>現在の商品名 :</span>
                      {{$orderDetail->product->name}}
                    </div>
                    <div class="p-4 text-lg font-semibold text-slate-500">
                      <span>現在の単価 :</span>
                        {{$orderDetail->product->cost}}
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
          </div>
      </div>
  </div>
</x-app-layout>