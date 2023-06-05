@extends('contact.layout')

@section('title', '問い合わせフォーム')

@section('content')
  <form method="post" action="{{ route('contact.confirm') }}">
    @csrf
    <label for="">メールアドレス</label>
    <input type="text" name="email" value="{{ old('email') }}">
    @if ( $errors->has('email') )
      <p class="error-message">{{ $errors->first('email') }}</p>
    @endif
    <br>
    <label for="">タイトル</label>
    <input type="text" name="title" value="{{ old('title') }}">
    @if ( $errors->has('title') )
      <p class="error-message">{{ $errors->first('title') }}</p>
    @endif
    <br>
    <label for="">お問い合わせ内容</label>
    <textarea name="body" id="" cols="30" rows="10">{{ old('body') }}</textarea>
    @if ( $errors->has('body') )
      <p class="error-message">{{ $errors->first('body') }}</p>
    @endif
    <br>
    <button type="submit">入力内容確認</button>
  </form>
@endsection