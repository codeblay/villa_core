@extends('layouts.blank.index')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('template/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner" style="max-width: 800px">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-8 mt-2">
                            <div class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros', [
                                    'width' => 100,
                                    'withbg' => 'var(--bs-primary)',
                                ])</span>
                                {{-- <span class="app-brand-text demo text-body fw-bold">{{ config('app.name') }}</span> --}}
                            </div>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2 text-center">Welcome to {{ config('app.name') }}! 👋</h4>
                        <hr class="my-4">
                        <div class="text-center">
                            @if ($status)                                
                                <img src="{{ asset('image/undraw/happy.svg') }}" height="400">
                                <p class="my-4 text-center">Verifikasi berhasil</p>
                            @else
                                <img src="{{ asset('image/undraw/sad.svg') }}" height="400">
                                <p class="my-4 text-center">Verifikasi gagal</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
