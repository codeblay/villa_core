@extends('layouts.admin.index' )
@section('title', 'List Destinasi')

@section('action')
    <button class="btn btn-success btn-sm">Tambah</button>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($destinations as $destination)
                        <tr>
                            <td><span class="fw-medium">{{ $destination->name }}</span></td>
                            <td>{{ $destination->category->name }}</td>
                            <td>{{ $destination->city->address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="3">Data tidak ada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection