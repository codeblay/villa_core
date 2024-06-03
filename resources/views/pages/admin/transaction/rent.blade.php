@extends('layouts.admin.index')
@section('title', 'Penyewaan')

@section('content')
    <div class="card mb-2">
        <form action="">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div style="flex-grow: 2">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" name="code" value="{{ request('code') }}"
                                placeholder="Cari kode booking..." aria-label="Cari kode booking..."
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
            <table class="table {{ count($transactions) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Villa</th>
                        <th>Pelanggan</th>
                        <th>Pembayaran</th>
                        <th>Harga</th>
                        <th>Biaya</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->code }}</td>
                            <td>{{ $transaction->villaType->villa->name }}</td>
                            <td>{{ $transaction->buyer->name }}</td>
                            <td>{{ $transaction->bank->name }}</td>
                            <td>{{ rupiah($transaction->amount) }}</td>
                            <td>{{ rupiah($transaction->fee) }}</td>
                            <td>{{ $transaction->created_at->translatedFormat('j F Y') }}</td>
                            <td>
                                <span
                                    class="badge bg-label-{{ $transaction->status_class }}">{{ $transaction->status_label }}</span>
                            </td>
                            <td class="text-end">
                                @if (!$transaction->is_manual)
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        @if (in_array($transaction->status, [0, 1]))
                                            <form form action="{{ route('admin.transaction.rentSync', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="4">
                                                <button type="submit" class="btn btn-icon btn-danger btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Tolak">
                                                    <span class="tf-icons bx bx-x"></span>
                                                </button>
                                            </form>
                                            <form form action="{{ route('admin.transaction.rentSync', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="3">
                                                <button type="submit" class="btn btn-icon btn-success btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Terima">
                                                    <span class="tf-icons bx bx-check"></span>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-icon btn-secondary btn-sm"
                                                style="cursor: not-allowed" data-bs-toggle="tooltip"
                                                data-bs-original-title="Selesai">
                                                <span class="tf-icons bx bx-check-double"></span>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    @if ($transaction->can_sync)
                                        <form action="{{ route('admin.transaction.rentSync', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-icon btn-warning btn-sm"
                                                data-bs-toggle="tooltip" data-bs-original-title="Sinkron">
                                                <span class="tf-icons bx bx-sync"></span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-icon btn-secondary btn-sm"
                                            style="cursor: not-allowed" data-bs-toggle="tooltip"
                                            data-bs-original-title="Sinkron">
                                            <span class="tf-icons bx bx-sync"></span>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="9">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($transactions->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection
