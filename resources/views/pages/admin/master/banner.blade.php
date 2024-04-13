@extends('layouts.admin.index')
@section('title', 'Banner')

@section('content')
    <div class="card p-4">
        @if ($banner_exist)
            <img src="{{ asset($banner) }}" id="uploadedAvatar" class="mb-4" height="800" style="object-fit: contain" />
        @else
            <div class="alert alert-danger mb-4" id="alert">Banner tidak ditemukan
            </div>
            <img src="{{ asset($banner) }}" id="uploadedAvatar" class="mb-4" height="800" style="object-fit: contain"
                hidden />
        @endif

        <div class="d-flex align-items-center justify-content-center gap-2">
            @if ($banner_exist)
                <form action="{{ route('admin.master.banner.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            @endif

            <label for="upload" class="btn btn-primary" tabindex="0">
                <span class="d-none d-sm-block">Browse</span>
                <i class="bx bx-upload d-block d-sm-none"></i>
            </label>
            <form action="{{ route('admin.master.banner.update') }}" enctype="multipart/form-data" method="POST"
                id="formSave" hidden>
                @csrf
                <input type="file" id="upload" class="account-file-input" name="banner" hidden
                    accept="application/jpg" />
                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
@endsection


@section('page-script')
    <script src="{{ asset('template/js/pages-account-settings-account.js') }}"></script>
    <script>
        $('#upload').change(function() {
            $('#alert').attr('hidden', true)
            $('#uploadedAvatar').attr('hidden', false)
            $('#formSave').attr('hidden', false)
        })
    </script>
@endsection
