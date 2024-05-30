<script>
    select2Option = {
        theme: "bootstrap",
        width: 'resolve',
        allowClear: true,
    }

    select2AjaxOption = {
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
    }

    $('.select2').select2(select2Option)

    $('.modal .select2').select2({
        ...select2Option,
        dropdownParent: $('.modal'),
    })

    $('.select2-ajax').select2(select2AjaxOption)

    $('.modal .select2-ajax').select2({
        ...select2AjaxOption,
        dropdownParent: $('.modal'),
    })

    $('.select2.multiple').select2({
        ...select2Option,
        multiple: true,
    })

    $('.modal .select2.multiple').select2({
        ...select2Option,
        dropdownParent: $('.modal'),
        multiple: true,
    })

    $('.select2-ajax.multiple').select2({
        ...select2AjaxOption,
        multiple: true,
    })

    $('.modal .select2-ajax.multiple').select2({
        ...select2AjaxOption,
        dropdownParent: $('.modal'),
        multiple: true,
    })
</script>
