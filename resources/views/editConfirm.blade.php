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
                  <div class="flex p-2 border rounded-md justify-center">
                    <div class="">
                      @if (!empty(session('tmpImageFileName')))
                        <img class="max-h-60 max-w-60" src="/storage/tmp/{{ session('tmpImageFileName') }}">
                      @else
                        <img class="max-h-60 max-w-60" src="/storage/{{$product->image}}">
                      @endif
                    </div>
                    <div class="">
                      <div class="flex border-b">
                        <div class="p-4">
                          商品名 : {{ $inputs['name'] }}
                          <input type="hidden" name="name" value="{{ $inputs['name'] }}">
                        </div>
                        <div class="p-4 text-lg font-semibold text-slate-500">
                          単価 : {{ $inputs['cost'] }}
                          <input type="hidden" name="cost" value="{{ $inputs['cost'] }}">
                        </div>
                      </div>
                      <div class="flex">
                        <div class="p-3">
                          <x-primary-button type="submit" class="" name="action" value="submit">
                            更新する
                          </x-primary-button>
                        </div>
                        <div class="p-3">
                          <x-secondary-button type="submit" class="" name="action" value="back">
                            修正する
                          </x-secondary-button>
                        </div>
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