@extends('layouts.admin.index')
@section('title', 'Edit')

@section('content')
    <div class="card">
        <form action="{{ route('admin.master.destination.list.update') }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $destination->id }}">
            <div class="card-body">
                <div class="row">
                    <div class="col col-12 mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" class="form-control" name="name"
                            placeholder="Masukkan kategori" value="{{ $destination->name }}" required>
                    </div>
                    <div class="col col-12 col-md-6 mb-3">
                        <label for="destination_category_id" class="form-label">Kategori</label>
                        <select class="form-select select2" id="destination_category_id" name="destination_category_id"
                            data-placeholder="Kategori" required>
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $destination->destination_category_id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col col-12 col-md-6 mb-3">
                        <label for="city_id" class="form-label">Lokasi</label>
                        <select class="form-select select2-ajax" id="city_id" name="city_id"
                            data-ajax--url="{{ route('api.select2.location') }}" data-placeholder="Lokasi" required>
                            <option value="{{ $destination->city_id }}" selected>{{ $destination->city->address }}</option>
                        </select>
                    </div>
                    <div class="col col-12 mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" class="form-control" name="description" placeholder="Masukkan deskripsi" rows="6"
                            required>{{ $destination->description }}</textarea>
                    </div>
                    <div class="col col-12">
                        <label class="form-label">Gambar</label>
                        <div class="d-flex flex-column align-items-start gap-2">
                            <div style="width: 20%">
                                <img src="{{ asset($destination->image_path) }}" alt="user-avatar" class="d-block rounded"
                                    id="uploadedAvatar" style="width: -webkit-fill-available" />
                            </div>
                            <div class="d-flex w-100 justify-content-start gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary account-image-reset">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>
                                <label for="upload" class="btn btn-sm btn-primary me-2" tabindex="0">
                                    <span class="d-none d-sm-block">Browse</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" class="account-file-input" name="image" hidden
                                        accept="image/jpeg" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body border-top text-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('template/js/pages-account-settings-account.js') }}"></script>
@endsection
