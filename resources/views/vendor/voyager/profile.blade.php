@extends('voyager::master')

@section('css')
    <style>
        .user-email {
            font-size: .85rem;
            margin-bottom: 1.5em;
        }
    </style>
@stop

@section('content')
    <div style="background-size:cover; background-image: url({{ Voyager::image( Voyager::setting('admin.bg_image'), config('voyager.assets_path') . '/images/bg.jpg') }}); background-position: center center;position:absolute; top:0; left:0; width:100%; height:300px;"></div>
    <div style="height:160px; display:block; width:100%"></div>
    <div style="position:relative; z-index:9; text-align:center;">
    <?php
    if (!filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL)) {
        if ( file_exists( '.'.Storage::url(Auth::user()->avatar) )) {
            $user_avatar = asset(Storage::url(Auth::user()->avatar));
        } else {
            $user_avatar = Avatar::create( Auth::user()->name )->toBase64();
        }
    } else {
        $user_avatar = Auth::user()->avatar;
    }
    ?>    
        <img src="{{ $user_avatar }}"
             class="avatar"
             style="border-radius:50%; width:150px; height:150px; border:5px solid #fff;"
             alt="{{ Auth::user()->name }} avatar">
        <h4>{{ ucwords(Auth::user()->name) }}</h4>
        <div class="user-email text-muted">{{ ucwords(Auth::user()->email) }}</div>
        <p>{{ Auth::user()->bio }}</p>
        <a href="{{ route('voyager.users.edit', Auth::user()->id) }}" class="btn btn-primary">{{ __('voyager::profile.edit') }}</a>
    </div>
@stop