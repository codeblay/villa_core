@extends('layouts.admin.index')
@section('title', 'Properti Villa')

@section('content')
    <div class="card mb-2">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div style="flex-grow: 2">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari villa..." aria-label="Cari villa..."
                            aria-describedby="basic-addon-search31" />
                    </div>
                </div>
                <div style="flex-grow: 1">
                    <select class="form-select">
                        <option>Lokasi</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
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
                        <th>Villa</th>
                        <th>Lokasi</th>
                        <th>Pemilik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($villas as $villa)
                        <tr>
                            <td><span class="fw-medium">{{ $villa->name }}</span></td>
                            <td>{{ $villa->city->address }}</td>
                            <td>{{ $villa->seller->name }}</td>
                            <td><span
                                    class="badge bg-label-{{ $villa->is_publish ? 'primary' : 'secondary' }} me-1">{{ $villa->publish_label }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-primary btn-sm" data-bs-toggle="tooltip"
                                    data-bs-original-title="Detail">
                                    <span class="tf-icons bx bx-show"></span>
                                </button>
                            </td>
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
