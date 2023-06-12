<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
            </div>
            <!--getメソッドなのでログインしてURL指定すると遷移できてしまうのを防ぐ-->
            @if (empty((session('carts'))))
              カートが空です。不正な画面遷移です。
            @else
              購入者情報は以下でよろしいですか？
              <form method="post" action="{{ route('cart.complete') }}">
                @csrf
                <label for="">名前</label>
                {{ Auth::user()->name }}
                <input type="hidden" name="title" value="{{ Auth::user()->name }}">
                @if ( $errors->has('title') )
                  <p class="error-message">{{ $errors->first('title') }}</p>
                @endif
                <br>
                <label for="">メールアドレス</label>
                {{ Auth::user()->email }}
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
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
                <button type="submit">注文を確定する</button>
              </form>
            @endif
          </div>
      </div>
  </div>
</x-app-layout>