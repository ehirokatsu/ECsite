<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

              </div>
              <form method="POST" action="{{ route('databaseManage.export') }}">
                @csrf
                <label for="user">User</label>
                <input id="user" type="radio" name="database" value="user">
                <label for="product">Product</label>
                <input id="product" type="radio" name="database" value="product">
                <button type="submit">エクスポート</button>
              </form>

          </div>
      </div>
  </div>
</x-app-layout>