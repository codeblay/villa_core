<!-- BEGIN: Vendor JS-->
<script src="{{ asset('template/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('template/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('template/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('template/vendor/js/menu.js') }}"></script>
<script src="{{ asset('template/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@include('components.select2')

<script>
    $(function() {
        let toast = $('body .bs-toast.show')
        let hasToast = toast.length > 0

        if (hasToast) {
            setTimeout(function() {
                toast.removeClass('show');
            }, 3000);
        }
    })

    function logout() {
        $('#logoutForm').submit()
    }

    $(window).bind('beforeunload', function() {
        $('#myLoading').removeClass('d-none')
        $('#myLoading').addClass('d-flex')
    });

    $(window).bind('unload', function() {
        $('#myLoading').addClass('d-none')
        $('#myLoading').removeClass('d-flex')
    });
</script>

@yield('page-script')
