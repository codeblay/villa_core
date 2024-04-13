@extends('layouts.admin.index')
@section('title', 'Mutasi Investor')

@section('action')
    <button class="btn btn-success btn-sm" id="addButton">Penarikan</button>
@endsection

@section('content')
    <div class="card mb-2">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $seller->name }}</h5>
                <h5 class="mb-0">{{ rupiah($balance) }}</h5>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table {{ count($mutations) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Transaksi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($mutations as $mutation)
                        <tr>
                            <td>{{ $mutation->created_at->translatedFormat('j F Y ~ H:i') }} WIB</td>
                            <td>{{ $mutation->type_label }}</td>
                            <td>{{ $mutation->transaction->code ?? 'System' }}</td>
                            <td class="text-end fw-medium {{ $mutation->amount > 0 ? 'text-success' : 'text-danger'}}">{{ rupiah(abs($mutation->amount)) }}</td>
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

    @if ($mutations->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $mutations->links() }}
        </div>
    @endif

    <div class="modal fade" id="modalAdd" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.user.seller.mutation.store', $seller->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddTitle">Penarikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="amount" class="form-label">Penarikan</label>
                            <input type="number" min="0" id="amount" class="form-control" name="amount"
                                placeholder="Jumlah penarikan">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="commition" class="form-label">Komisi</label>
                            <input type="number" min="0" id="commition" class="form-control" name="commition"
                                placeholder="Jumlah komisi">
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