@php
    $width = $width ?? '25';
    $withbg = $withbg ?? '#696cff';
@endphp
@if (file_exists(public_path('image/logo.png')))
    <img src="{{ asset('image/logo.png') }}" width="{{ $width }}" alt="">
@endif
