@extends('layouts.admin.index' )
@section('title', 'Pemilik Properti')

@section('content')
    <div class="card mb-2">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div style="flex-grow: 2">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari penyewa..." aria-label="Cari penyewa..."
                            aria-describedby="basic-addon-search31" />
                    </div>
                </div>
                <div style="flex-grow: 1">
                    <select class="form-select">
                        <option>Status</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div style="flex-grow: 0">
                    <button type="button" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Umur</th>
                        <th>Total Villa</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($sellers as $seller)
                        <tr>
                            <td><span class="fw-medium">{{ $seller->name }}</span></td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->gender }}</td>
                            <td>{{ $seller->age }} Tahun</td>
                            <td>{{ $seller->villas_count }} Unit</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="5">Data tidak ada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
