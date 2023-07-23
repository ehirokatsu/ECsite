<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
              </div>
                @foreach($orderDetails as $orderDetail)
                  <div class="flex p-2 border rounded-md justify-center">
                    <div class="">
                      <div class="flex border-b">
                        <div class="p-4">
                          <span>注文ID :</span>
                          {{$orderDetail->id}}
                        </div>
                        <div class="p-4 text-lg font-semibold text-slate-500">
                          <span>注文日 : </span>
                          現在のユーザーメール：{{$orderDetail->order->user->email}}
                          注文時のユーザーメール{{$orderDetail->order->email}}
                          {{$orderDetail->product->image}}
                        </div>
                      </div>
                      <div class="">
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
          </div>
      </div>
  </div>
</x-app-layout>