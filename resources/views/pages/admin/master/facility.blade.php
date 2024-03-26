@extends('layouts.admin.index')
@section('title', 'Fasilitas')

@section('action')
    <button class="btn btn-success btn-sm" id="addButton">Tambah</button>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table {{ count($facilities) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Fasilitas</th>
                        <th>Total Villa</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($facilities as $facility)
                        <tr>
                            <td><span class="fw-medium">{{ $facility->name }}</span></td>
                            <td>{{ $facility->villas_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalAdd" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.master.facility.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddTitle">Tambah Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" class="form-control" name="name"
                                placeholder="Masukkan fasilitas">
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
