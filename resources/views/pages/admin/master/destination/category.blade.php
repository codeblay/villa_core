@extends('layouts.admin.index')
@section('title', 'Destinasi Kategori')

@section('action')
    <button class="btn btn-success btn-sm" id="addButton">Tambah</button>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table {{ count($categories) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Total Destinasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($categories as $category)
                        <tr>
                            <td><span class="fw-medium">{{ $category->name }}</span></td>
                            <td>{{ $category->destinations_count }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    <form action="{{ route('admin.master.destination.category.delete', $category->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-danger btn-sm deleteButton" data-total-destination="{{ $category->destinations_count }}"
                                            data-bs-toggle="tooltip" title="Hapus">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-icon btn-warning btn-sm editButton"
                                        data-bs-toggle="tooltip" title="Edit" data-url="{{ route('admin.master.destination.category.update', $category->id) }}" data-name="{{ $category->name }}">
                                        <span class="tf-icons bx bx-pencil"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="3">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalAdd" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.master.destination.category.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddTitle">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" class="form-control" name="name"
                                placeholder="Masukkan kategori" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditTitle">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" class="form-control" name="name"
                                placeholder="Masukkan kategori">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalDeleteFailed" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('image/undraw/warning.svg') }}" height="200">
                        <div class="mt-3">
                            <span class="badge bg-label-danger text-wrap lh-base">Kategori telah digunakan oleh beberapa destinasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $('#addButton').click(function(e) {
            e.preventDefault()
            $('#modalAdd').modal('show')
        })

        $('.deleteButton').click(function(e) {
            e.preventDefault()

            let totalDestinasi = $(this).data('total-destination')

            if (totalDestinasi > 0) {
                $('#modalDeleteFailed').modal('show')
            } else {
                $(this).closest('form').submit()
            }
        })

        $('.editButton').click(function(e) {
            e.preventDefault()
            
            let url = $(this).data('url')
            let name = $(this).data('name')

            $('#modalEdit form').attr('action', url)
            $('#modalEdit [name="name"]').val(name)
            $('#modalEdit').modal('show')
        })
    </script>
@endsection
