@extends('layouts.blank.index')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('template/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        
                        <!-- /Logo -->
                        <h4 class="mb-2 text-center">Welcome to {{ config('app.name') }}! 👋</h4>
                        <hr class="my-4">
                        <div class="text-center">
                            <img src="{{ asset('image/logo.png') }}" style="max-width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
