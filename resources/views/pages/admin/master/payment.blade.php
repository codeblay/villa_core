@extends('layouts.admin.index')
@section('title', 'Pembayaran')

@section('content')
    <div class="d-flex flex-wrap gap-3">
        @foreach ($banks as $bank)
            <div class="card flex-grow-1 flex-md-grow-0 editButton">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <img src="{{ $bank->logo }}" alt="" height="200">
                        <p class="mb-0">{{ $bank->name }}</p>
                        <span
                            class="badge bg-label-{{ $bank->is_active ? 'success' : 'danger' }}">{{ $bank->is_active_label }}</span>
                        <form action="{{ route('admin.master.payment.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $bank->id }}">
                            <input type="hidden" name="is_active" value="{{ $bank->is_active }}">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Fee</span>
                                    <input type="number" name="fee" class="form-control" min="0" placeholder="0" value="{{ $bank->fee }}">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" {{ $bank->is_active ? 'checked' : '' }}>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection


@section('page-script')
    <script>
        $('.form-switch [type="checkbox"]').change(function() {
            const FORM = $(this).closest('form')
            let isChecked = $(this).is(':checked') ? 1 : 0

            FORM.find('[name="is_active"]').val(isChecked)
            FORM.submit()
        })
    </script>
@endsection
