<!-- BEGIN: Vendor JS-->
<script src="{{ asset('template/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('template/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('template/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('template/vendor/js/menu.js') }}"></script>
<script src="{{ asset('template/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        $('.select2').select2({
            theme: "bootstrap",
            width: 'resolve',
            minimumInputLength: 3,
            language: {
                inputTooShort: function() {
                    return "Minimal 3 karakter";
                },
                noResults: function() {
                    return "Data tidak ditemukan"
                },
                searching: function() {
                    return "Mencari data.."
                },
                errorLoading: function() {
                    return "Terjadi kesalahan, coba lagi"
                },
            },
            allowClear: true,
            ajax: {
                data: function(term) {
                    return {
                        keyword: term.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
        })
    })

    function logout() {
        $('#logoutForm').submit()
    }
</script>

@yield('page-script')
