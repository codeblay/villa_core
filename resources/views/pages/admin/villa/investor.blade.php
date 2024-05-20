@extends('layouts.admin.index')
@section('title', 'Villa')

@section('action')
    <button class="btn btn-success btn-sm" id="btnAdd">Tambah</button>
@endsection

@section('content')
    <div class="card mb-2">
        <form action="">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div style="flex-grow: 1">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                                placeholder="Cari villa..." aria-label="Cari investor..."
                                aria-describedby="basic-addon-search31" />
                        </div>
                    </div>
                    <div style="flex-grow: 0">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table {{ count($items) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($items as $item)
                        <tr>
                            <td><span class="fw-medium">{{ $item->investor->name }}</span></td>
                            <td><span class="fw-medium">{{ $item->investor->email }}</span></td>
                            <td><span class="fw-medium">{{ $item->investor->phone }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('admin.villa.investor.delete', $villa_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-icon btn-danger btn-sm" data-bs-toggle="tooltip"
                                        data-bs-original-title="Hapus">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="7">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($items->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $items->links() }}
        </div>
    @endif

    <div class="modal fade" id="modalAdd" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.villa.investor.store', $villa_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddTitle">Tambah Investor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="villa_id" value="{{ $villa_id }}">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="rating">Investor</label>
                            <select class="form-select select2-ajax" name="investor_id"
                                data-ajax--url="{{ route('api.select2.investor.villa', $villa_id) }}"
                                data-placeholder="Investor">
                            </select>
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
        $('#btnAdd').click(function(e) {
            e.preventDefault()
            $('#modalAdd').modal('show')
        })
    </script>
@endsection
