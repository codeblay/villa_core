<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="{{ asset('template/vendor/fonts/boxicons.css') }}" />

<link rel="stylesheet" href="{{ asset('template/vendor/css/core.css') }}" />
<link rel="stylesheet" href="{{ asset('template/vendor/css/theme-default.css') }}" />
<link rel="stylesheet" href="{{ asset('template/css/demo.css') }}" />
<link rel="stylesheet" href="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">

<style>
    .select2 {
        width: 100% !important;
    }

    .select2-container .select2-selection {
        width: 100% !important;
        padding-bottom: 2rem !important;
        border-radius: var(--bs-border-radius) !important;
    }

    .select2-container .select2-selection__rendered {
        color: #697a8d !important;
        font-weight: 400 !important;
        padding-top: 0.25rem !important;
    }

    .select2-container .select2-selection__clear {
        color: red !important;
        margin-top: 4px !important;
    }

    .select2-container .select2-dropdown {
        margin-top: -4px !important;
    }
</style>

@yield('page-style')
