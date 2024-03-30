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
                        <p class="mb-4 text-center">Silahkan masukkan password baru anda</p>

                        <form class="mb-3" action="{{ route('reset') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reset_token" value="{{ @$token }}">
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
