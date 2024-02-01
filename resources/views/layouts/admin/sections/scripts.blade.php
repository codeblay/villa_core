<!-- BEGIN: Vendor JS-->
<script src="{{ asset('template/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('template/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('template/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('template/vendor/js/menu.js') }}"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset('template/js/main.js') }}"></script>

<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
