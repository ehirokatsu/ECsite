<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ECsite
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <form action="/{{$product->id}}" method="post" enctype="multipart/form-data">
                  @method('PUT')
                  @csrf
                  <span>商品名</span>
                  <input type="text" name="name" value="{{ $product->name }}">
                  <br>
                  <span>単価</span>
                  <input type="text" name="cost" value="{{ $product->cost }}">
                  <br>
                  <span>商品画像</span>
                  <input type="file" name="image">
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