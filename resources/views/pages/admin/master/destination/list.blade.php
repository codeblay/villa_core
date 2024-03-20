@extends('layouts.admin.index')
@section('title', 'List Destinasi')

@section('action')
    <button class="btn btn-success btn-sm" id="addButton">Tambah</button>
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
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($destinations as $destination)
                        <tr>
                            <td><span class="fw-medium">{{ $destination->name }}</span></td>
                            <td>{{ $destination->category->name }}</td>
                            <td>{{ $destination->city->address }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-icon btn-primary btn-sm detail-button" data-bs-toggle="tooltip"
                                    data-bs-original-title="Detail" data-url="{{ route('admin.master.destination.list.detail', $destination->id) }}">
                                    <span class="tf-icons bx bx-show"></span>
                                </button>
                            </td>
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

    <div class="modal fade" id="modalAdd" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.master.destination.list.create') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddTitle">Tambah Destinasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col col-12 mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" class="form-control" name="name"
                                placeholder="Masukkan kategori" required>
                        </div>
                        <div class="col col-12 col-md-6 mb-3">
                            <label for="destination_category_id" class="form-label">Kategori</label>
                            <select class="form-select select2" id="destination_category_id"
                                name="destination_category_id" data-placeholder="Kategori" required>
                                <option value=""></option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col col-12 col-md-6 mb-3">
                            <label for="city_id" class="form-label">Lokasi</label>
                            <select class="form-select select2-ajax" id="city_id" name="city_id"
                                data-ajax--url="{{ route('api.select2.location') }}" data-placeholder="Lokasi" required>
                            </select>
                        </div>
                        <div class="col col-12 mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea id="description" class="form-control" name="description" placeholder="Masukkan deskripsi" rows="6"
                                required></textarea>
                        </div>
                        <div class="col col-12">
                            <label class="form-label">Gambar</label>
                            <div class="d-flex flex-column align-items-start gap-2">
                                <div style="width: 60%">
                                    <img src="{{ asset('image/placeholder-16-9.jpg') }}" alt="user-avatar" class="d-block rounded"
                                        id="uploadedAvatar" style="width: -webkit-fill-available"/>
                                </div>
                                <div class="d-flex w-100 justify-content-start gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary account-image-reset">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <label for="upload" class="btn btn-sm btn-primary me-2" tabindex="0">
                                        <span class="d-none d-sm-block">Browse</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" class="account-file-input" name="image"
                                            hidden accept="image/jpeg"/>
                                    </label>
                                </div>
                            </div>
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
    <script src="{{ asset('template/js/pages-account-settings-account.js') }}"></script>
    <script>
        $('#addButton').click(function(e) {
            e.preventDefault()
            $('#modalAdd').modal('show')
        })
        
        $('.detail-button').click(function(e) {
            e.preventDefault()
            window.location.href = $(this).data('url')
        })
    </script>
@endsection
