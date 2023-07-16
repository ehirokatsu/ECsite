<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <form action="{{ route('createConfirm') }}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="">
                    <div class="p-4">
                      <span>商品名:
                      </span>
                      <input type="text" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="p-4">
                      <span>単価:</span>
                      <input type="text" name="cost" value="{{ old('cost') }}">
                    </div>
                    <div class="p-4">
                      <span>商品画像</span>
                      <input type="file" name="image"  value="{{ old('image') }}">
                    </div>
                    <x-primary-button type="submit" class="">
                      確認する
                    </x-primary-button>
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