@extends('layouts.admin.index')
@section('title', 'Pembayaran')

@section('content')
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table {{ count($banks) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Nomor Virtual Akun</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($banks as $bank)
                        <tr>
                            <td hidden>
                                <span data-key="name" data-value="{{ $bank->name }}"></span>
                                <span data-key="logo" data-value="{{ $bank->logo }}"></span>
                                <span data-key="va_number" data-value="{{ $bank->va_number }}"></span>
                                <span data-key="status" data-value="{{ $bank->is_active }}"></span>
                                <span data-key="id" data-value="{{ $bank->id }}"></span>
                                <span data-key="code" data-value="{{ $bank->code }}"></span>
                            </td>

                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <img src="{{ $bank->logo }}" height="30">
                                    <span class="fw-medium">{{ $bank->name }}</span>
                                </div>
                            </td>
                            <td>{{ $bank->va_number ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $bank->is_active ? 'success' : 'danger' }}">{{ $bank->is_active_label }}</span>
                            </td>
                            <td class="text-end"><button class="btn btn-warning btn-sm editButton" data-bs-toggle="tooltip"
                                    title="Edit">
                                    <span class="tf-icons bx bx-pencil"></span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="4">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.master.payment.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="code" id="code" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditTitle">Edit Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col col-12 mb-3 text-center">
                            <img id="logo" src="" alt="" height="200">
                        </div>

                        <div class="col col-12 mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" class="form-control" value="" readonly>
                        </div>

                        <div class="col col-12 mb-3">
                            <label for="va_number" class="form-label">Nomor Virtual Akun</label>
                            <input type="text" id="va_number" name="va_number" class="form-control" value="">
                        </div>

                        <div class="col col-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input name="is_active" class="form-check-input" type="radio" value="0"
                                        id="statusNonActive">
                                    <label class="form-check-label" for="statusNonActive">
                                        Tidak Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="is_active" class="form-check-input" type="radio" value="1"
                                        id="statusActive">
                                    <label class="form-check-label" for="statusActive">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $('.editButton').click(function(e) {
            e.preventDefault()

            let id = $(this).closest('tr').find("[data-key='id']").data('value');
            let logo = $(this).closest('tr').find("[data-key='logo']").data('value');
            let code = $(this).closest('tr').find("[data-key='code']").data('value');
            let name = $(this).closest('tr').find("[data-key='name']").data('value');
            let va_number = $(this).closest('tr').find("[data-key='va_number']").data('value');
            let status = $(this).closest('tr').find("[data-key='status']").data('value');

            $('#modalEdit #id').val(id)
            $('#modalEdit #logo').attr('src', logo)
            $('#modalEdit #code').val(code)
            $('#modalEdit #name').val(name)
            $('#modalEdit #va_number').val(va_number)

            $(`#modalEdit [name="is_active"]`).attr('checked', false)
            $(`#modalEdit [name="is_active"][value="${status}"]`).attr('checked', true)

            $('#modalEdit #va_number').closest('.col').attr('hidden', code == 'qr');

            $('#modalEdit').modal('show')
        })
    </script>
@endsection
