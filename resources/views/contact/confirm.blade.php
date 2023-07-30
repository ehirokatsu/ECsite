<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-lg mx-auto sm:px-6 lg:px-8 ">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900  flex justify-center items-center">
                <form method="post" action="{{ route('contact.send') }}">
                  @csrf
                  <div class="">
                    <x-input-label for="email" />メールアドレス
                    <x-input-label for="email" />{{ $inputs['email'] }}
                    <!--sendコントローラに送るためにhiddenをセットする-->
                    <input type="hidden" name="email" value="{{ $inputs['email'] }}">
                  </div>

                  <div>
                    <x-input-label for="title" />件名
                    <x-input-label for="title" />{{ $inputs['title'] }}
                    <input type="hidden" name="title" value="{{ $inputs['title'] }}">
                  </div>


                  <div>
                    <x-input-label for="body" />本文
                    <!--改行コードをそのまま出力（nl2br）し、エスケープ処理（e）を行う-->
                    <x-input-label for="body" />{!! nl2br(e($inputs['body'])) !!}
                    <input type="hidden" name="body" value="{{ $inputs['body'] }}">
                  </div>

                  <div class="p-2 flex justify-end mt-4">
                    <x-secondary-button type="submit" class="m-2" name="action" value="back">入力内容確認</x-secondary-button>
                    <x-primary-button type="submit" class="m-2" name="action" value="submit">送信する</x-primary-button>
                  </div>
                </form>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>