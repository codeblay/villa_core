<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    @if (!isset($navbarHideToggle))
        <div
            class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>
    @endif

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="card-header">@yield('title')</h5>
            <div>
                @yield('action')
            </div>
        </div>
    </div>

</nav>
