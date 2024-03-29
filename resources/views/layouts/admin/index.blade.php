<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}"
    data-base-url="{{ url('/') }}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', config('app.name'))</title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}" />

    @include('layouts.admin.sections.styles')
    @include('layouts.admin.sections.scriptsIncludes')
</head>

<body>
    <div class="d-none align-items-center justify-content-center" id="myLoading">
        <div class="spinner-border spinner-border-lg text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
    </div>

    @php
        $contentNavbar = true;
        $containerNav = $containerNav ?? 'container-fluid';
        $isNavbar = $isNavbar ?? true;
        $isMenu = $isMenu ?? true;
        $isFlex = $isFlex ?? false;
        $isFooter = $isFooter ?? true;
        $navbarDetached = 'navbar-detached';
        $container = $container ?? 'container-fluid';
    @endphp

    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container">

            @if ($isMenu)
                @include('layouts.admin.sections.menu.verticalMenu')
            @endif

            <div class="layout-page">
                @if ($isNavbar)
                    @include('layouts.admin.sections.navbar.navbar')
                @endif

                <div class="content-wrapper">

                    @if ($isFlex)
                        <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
                        @else
                            <div class="{{ $container }} flex-grow-1 container-p-y">
                    @endif

                    @yield('content')

                </div>

                @if ($isFooter)
                    @include('layouts.admin.sections.footer.footer')
                @endif
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>

    @if ($isMenu)
        <div class="layout-overlay layout-menu-toggle"></div>
    @endif

    <div class="drag-target"></div>
    </div>

    <form action="{{ route('admin.logout') }}" method="POST" id="logoutForm">
        @csrf
    </form>

    @include('_partials.toast')

    @include('layouts.admin.sections.scripts')

</body>

</html>
