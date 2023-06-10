<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
              </div>
            購入者情報を入力してください。
            <form method="post" action="{{ route('cart.confirm') }}">
              @csrf
              <label for="">名前</label>
              <input type="text" name="title" value="{{ old('title') }}">
              @if ( $errors->has('title') )
                <p class="error-message">{{ $errors->first('title') }}</p>
              @endif
              <br>
              <label for="">メールアドレス</label>
              <input type="text" name="email" value="{{ old('email') }}">
              @if ( $errors->has('email') )
                <p class="error-message">{{ $errors->first('email') }}</p>
              @endif
              <!--
              <br>
              <label for="">電話番号</label>
              <textarea name="body" id="" cols="30" rows="10">{{ old('body') }}</textarea>
              @if ( $errors->has('body') )
                <p class="error-message">{{ $errors->first('body') }}</p>
              @endif
              <br>
              <label for="">住所</label>
              <textarea name="body" id="" cols="30" rows="10">{{ old('body') }}</textarea>
              @if ( $errors->has('body') )
                <p class="error-message">{{ $errors->first('body') }}</p>
              @endif
              -->
              <br>
              <button type="submit">入力内容確認</button>
            </form>
          </div>
      </div>
  </div>
</x-app-layout>