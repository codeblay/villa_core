@extends('layouts.admin.index' )
@section('title', 'Investor')

@section('content')
    <div class="card mb-2">
        <form action="">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div style="flex-grow: 2">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" name="name" value="{{ request('name') }}" placeholder="Cari investor..." aria-label="Cari investor..."
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
            <table class="table {{ count($sellers) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur</th>
                        <th>Villa</th>
                        <th class="text-end">Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($sellers as $seller)
                        <tr>
                            <td><span class="fw-medium">{{ $seller->name }}</span></td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->gender_label }}</td>
                            <td>{{ $seller->age }} Tahun</td>
                            <td>{{ $seller->villas_count }} Unit</td>
                            <td class="text-end">
                                <span class="badge bg-label-{{ $seller->is_verified ? 'success' : 'secondary' }} me-1">{{ $seller->is_verified ? 'Ya' : 'Tidak' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="6">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($sellers->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $sellers->links() }}
        </div>
    @endif
@endsection
