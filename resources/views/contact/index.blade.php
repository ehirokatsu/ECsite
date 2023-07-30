<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-lg mx-auto sm:px-6 lg:px-8 ">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900  flex justify-center items-center">
                <form method="post" action="{{ route('contact.confirm') }}">
                  @csrf
                  <div class="">
                    <x-input-label for="email" />メールアドレス
                    <x-text-input id="email" class="block mt-1 w-80" type="text" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                  </div>

                  <div>
                    <x-input-label for="title" />件名
                    <x-text-input id="title" class="block mt-1 w-80" type="text" name="title" :value="old('title')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                  </div>


                  <div>
                    <x-input-label for="body" />本文
                    <textarea id="body" class="block mt-1 w-80 p-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="body" :value="old('body')" required autofocus></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                  </div>

                  <div class="flex justify-end mt-4">
                    <x-primary-button type="submit" >入力内容確認</x-primary-button>
                  </div>
                </form>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>