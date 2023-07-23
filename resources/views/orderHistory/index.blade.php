<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
              </div>
             
              @if (!empty($orders))
                @foreach($orders as $order)
                  <div class="flex p-2 border rounded-md justify-center">
                    <div class="">
                      <div class="flex border-b">
                        <div class="p-4">
                          <span>注文ID :</span>
                          {{ $order->id }}
                        </div>
                        <div class="p-4 text-lg font-semibold text-slate-500">
                          <span>注文日 : </span>
                            {{ $order->ordered_on }}
                        </div>
                      </div>
                      <div class="">
                        <div class="p-3">
                          <a href="{{ route('orderHistory.show', ['id' => $order->id]) }}" class="" >
                            注文詳細
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <span>注文情報がありません。</span>
              @endif
          </div>
      </div>
  </div>
</x-app-layout>