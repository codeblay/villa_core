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
                        <th>Pemilik</th>
                        <th>Pelanggan</th>
                        <th>Pembayaran</th>
                        <th>Villa</th>
                        <th>Harga</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->code }}</td>
                            <td>{{ $transaction->villa->seller->name }}</td>
                            <td>{{ $transaction->buyer->name }}</td>
                            <td>{{ $transaction->bank->name }}</td>
                            <td>{{ $transaction->villa->name }}</td>
                            <td>{{ rupiah($transaction->amount) }}</td>
                            <td>{{ $transaction->created_at->translatedFormat('j F Y') }}</td>
                            <td>{{ ucfirst($transaction->status_label) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="8">@include('components.empty')</td>
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
