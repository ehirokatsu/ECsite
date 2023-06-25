<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <form action="{{ route('update', ['id' => $product->id]) }}" method="post" enctype="multipart/form-data">
                  @method('PUT')
                  @csrf
                  <span>商品名</span>
                  {{ $inputs['name'] }}
                  <input type="hidden" name="name" value="{{ $inputs['name'] }}">
                  <br>
                  <span>単価</span>
                  {{ $inputs['cost'] }}
                  <input type="hidden" name="cost" value="{{ $inputs['cost'] }}">
                  <br>
                  <span>商品画像</span>
                  <img class="" src="/storage/tmp/{{ $imageFileName }}">
                  <br>
                  <input type="submit">
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