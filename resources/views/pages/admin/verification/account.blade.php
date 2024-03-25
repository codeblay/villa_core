@extends('layouts.admin.index')
@section('title', 'Verifikasi Akun')

@section('content')
    <div class="card mb-2">
        <div class="card-header">
            <form action="">
                <div class="d-flex flex-wrap gap-2">
                    <div style="flex-grow: 2">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari akun..." aria-label="Cari akun..."
                                name="name" aria-describedby="basic-addon-search31" />
                        </div>
                    </div>
                    <div style="flex-grow: 0">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
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
                        <th>Tanggal Registrasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($sellers as $seller)
                        <tr>
                            <td><span class="fw-medium">{{ $seller->name }}</span></td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->gender }}</td>
                            <td>{{ $seller->age }} Tahun</td>
                            <td>{{ $seller->created_at->translatedFormat('j F Y') }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    <form action="{{ route('admin.verification.account.deny', $seller->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-danger btn-sm"
                                            data-bs-toggle="tooltip" title="Tolak">
                                            <span class="tf-icons bx bx-x"></span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.verification.account.accept', $seller->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-success btn-sm"
                                            data-bs-toggle="tooltip" title="Terima">
                                            <span class="tf-icons bx bx-check"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="6">Data tidak ada</td>
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
