@extends('layouts.admin.index')
@section('title', 'Kategori Destinasi')

@section('action')
    <button class="btn btn-success btn-sm" id="addButton">Tambah</button>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Total Destinasi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($categories as $category)
                        <tr>
                            <td><span class="fw-medium">{{ $category->name }}</span></td>
                            <td>{{ $category->destinations_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">Data tidak ada</td>
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
@endsection

@section('page-script')
    <script>
        $('#addButton').click(function(e) {
            e.preventDefault()
            $('#modalAdd').modal('show')
        })
    </script>
@endsection
