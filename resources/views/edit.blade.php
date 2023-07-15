<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <form action="{{ route('editConfirm', ['id' => $product->id]) }}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="flex p-2 border rounded-md justify-center">
                    <div class="">
                      <img class="max-h-60 max-w-60" src="/storage/{{$product->image}}">
                    </div>
                    <div class="">
                      <div class="flex border-b">
                        <div class="p-4">
                          商品名 : <input type="text" name="name" value="{{ $product->name }}">
                        </div>
                        <div class="p-4 text-lg font-semibold text-slate-500">
                          単価 : <input type="text" name="cost" value="{{ $product->cost }}">
                        </div>
                      </div>
                      <div class="p-4 text-sm font-medium text-slate-700 mt-2">
                        商品画像：<input type="file" name="image">
                      </div>
                      <div class="p-3">
                        <x-primary-button type="submit" class="">
                          確認する
                        </x-primary-button>
                      </div>
                    </div>
                  </div>
                </form>
                @if ($errors->any())
                <div class="">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
                </div>
                @endif
              </div>
          </div>
      </div>
  </div>
</x-app-layout>