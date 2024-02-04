<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('template/img/favicon/favicon.ico') }}" />
    @include('layouts.admin.sections.styles')
    @include('layouts.admin.sections.scriptsIncludes')
</head>

<body>

    @yield('content')
    
    @include('_partials.toast')

    @include('layouts.admin.sections.scripts')

</body>

</html>
