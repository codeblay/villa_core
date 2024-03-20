@extends('layouts.admin.index')
@section('title', 'Penyewa')

@section('content')
    <div class="card mb-2">
        <form action="">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div style="flex-grow: 2">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" name="name" value="{{ request('name') }}" placeholder="Cari penyewa..."
                                aria-label="Cari penyewa..." aria-describedby="basic-addon-search31" />
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
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur</th>
                        <th>Transaksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($buyers as $buyer)
                        <tr>
                            <td><span class="fw-medium">{{ $buyer->name }}</span></td>
                            <td>{{ $buyer->email }}</td>
                            <td>{{ $buyer->gender_label }}</td>
                            <td>{{ $buyer->age }} Tahun</td>
                            <td>{{ $buyer->transactions_success_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="4">Data tidak ada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($buyers->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $buyers->links() }}
        </div>
    @endif
@endsection
