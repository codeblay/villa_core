@extends('layouts.admin.index')
@section('title', 'Dokumen Verifikasi')

@section('content')
    <div class="card p-4">
        @if ($document_exist)
            <embed type="application/pdf" src="{{ asset($document) }}" id="uploadedAvatar" class="mb-4" height="800"></embed>
        @else
            <div class="alert alert-danger mb-4" id="alert">Silahkan unggah dokumen verifikasi agar user dapat melakukan
                registrasi.
            </div>
            <embed type="application/pdf" height="800" class="mb-4" id="uploadedAvatar" hidden></embed>
        @endif

        <div class="d-flex align-items-center justify-content-center gap-2">
            <label for="upload" class="btn btn-primary" tabindex="0">
                <span class="d-none d-sm-block">Browse</span>
            </label>
            <form action="{{ route('admin.master.document.update') }}" enctype="multipart/form-data" method="POST" id="formSave" hidden>
                @csrf
                <input type="file" id="upload" class="account-file-input" name="document" hidden
                    accept="application/pdf" />
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
