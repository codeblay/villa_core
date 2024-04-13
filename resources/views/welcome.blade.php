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
                        <div class="text-center">
                            <div class="d-inline-block p-2 rounded" style="background-color: #424242">
                                <img src="{{ asset('image/logo/icon.png') }}" height="50">
                            </div>
                        </div>
                        <hr class="my-4">
                        <h4 class="mb-2 text-center">Welcome to {{ config('app.name') }}! 👋</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
