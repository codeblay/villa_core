@extends('layouts.admin.index')
@section('title', 'Villa')

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            text-align: center;
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }
    </style>
@endsection

@section('content')
    <form class="row" action="{{ route('admin.villa.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $villa->id }}">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Villa</h5>
                <hr class="my-0">
                <div class="card-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $villa->name }}">
                    </div>
                    <div>
                        <label class="form-label">Lokasi</label>
                        <select class="form-select select2-ajax" name="city_id"
                            data-ajax--url="{{ route('api.select2.location') }}" data-placeholder="Lokasi">
                            <option value="{{ $villa->city->id }}" selected>{{ $villa->city->address }}</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <label class="form-label d-block mb-0">Gambar</label>
                        <div class="d-flex flex-wrap gap-2 image-preview">
                            @foreach ($villa->files as $file)
                                <div>
                                    <img src="{{ $file->local_path }}" style="width: 200px" class="show-image">
                                </div>
                            @endforeach
                        </div>
                        <label class="btn btn-sm btn-primary">
                            <span class="d-none d-sm-block">Browse</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input type="file" class="image" name="images[]" hidden accept="image/jpeg" multiple />
                        </label>
                        <small class="text-info">*Disarankan menggunakan gambar dengan resolusi 16:9</small>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea type="text" name="description" class="form-control" rows="5">{{ $villa->description }}</textarea>
                    </div>
                    <div>
                        <select class="form-select select2" name="status" data-placeholder="Status">
                            <option></option>
                            <option value="1" {{ $villa->is_publish == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $villa->is_publish == '0' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Unit</h5>

                <div id="sectionType">
                    @foreach ($villa->villaTypes as $type)
                        <div class="input-villa-type">
                            <input type="hidden" name="type[{{ $loop->index }}][id]" value="{{ $type->id }}">
                            <hr class="my-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" name="type[{{ $loop->index }}][name]" class="form-control"
                                            value="{{ $type->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Unit</label>
                                        <input type="text" name="type[{{ $loop->index }}][total_unit]"
                                            class="form-control" value="{{ $type->total_unit }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Harga Sewa / Malam</label>
                                        <input type="text" name="type[{{ $loop->index }}][price]" class="form-control"
                                            value="{{ $type->price }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fasilitas</label>
                                        <select class="form-select select2-ajax multiple"
                                            name="type[{{ $loop->index }}][facilities][]"
                                            data-ajax--url="{{ route('api.select2.facility') }}"
                                            data-placeholder="Fasilitas" multiple="multiple">
                                            @foreach ($type->facilities as $fc)
                                                <option selected value="{{ $fc->id }}">{{ $fc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3 d-flex flex-column gap-1">
                                        <label class="form-label d-block mb-0">Gambar</label>
                                        <div class="d-flex flex-wrap gap-2 image-preview-type" data-index="{{ $loop->index }}">
                                            @foreach ($type->files as $file)
                                                <div>
                                                    <img src="{{ $file->local_path }}" style="width: 200px"
                                                        class="show-image">
                                                </div>
                                            @endforeach
                                        </div>
                                        <label class="btn btn-sm btn-primary">
                                            <span class="d-none d-sm-block">Browse</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" class="image-type"
                                                name="type[{{ $loop->index }}][images][]" hidden accept="image/jpeg"
                                                data-index="{{ $loop->index }}" multiple />
                                        </label>
                                        <small class="text-info">*Disarankan menggunakan gambar dengan resolusi 16:9</small>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea type="text" name="type[{{ $loop->index }}][description]" class="form-control" rows="5">{{ $type->description }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <select class="form-select select2" name="type[{{ $loop->index }}][status]" data-placeholder="Status">
                                            <option></option>
                                            <option value="1" {{ $type->is_publish == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ $type->is_publish == '0' ? 'selected' : '' }}>Non Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-0" id="endLine">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm" id="removeType">Hapus</button>
                        <button type="button" class="btn btn-success btn-sm" id="addType">Tambah</button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>

        </div>
    </form>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $('#addType').click(function(e) {
            let index = $('.input-villa-type').length
            $('#sectionType').append(typeHtml(index))
            newSelect2('.select2-ajax.multiple')
        })

        $('#removeType').click(function(e) {
            let index = $('.input-villa-type').length
            if (index == 1) return

            $('.input-villa-type').last().remove()
        })

        var isType = false;
        var indexType = 0;
        var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;

        $(() => {
            changeImage()
            changeImageType()
        })

        function newSelect2(selector) {
            select2AjaxOption = {
                theme: "bootstrap",
                width: 'resolve',
                minimumInputLength: 3,
                language: {
                    inputTooShort: function() {
                        return "Minimal 3 karakter";
                    },
                    noResults: function() {
                        return "Data tidak ditemukan"
                    },
                    searching: function() {
                        return "Mencari data.."
                    },
                    errorLoading: function() {
                        return "Terjadi kesalahan, coba lagi"
                    },
                },
                allowClear: true,
                ajax: {
                    data: function(term) {
                        return {
                            keyword: term.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                },
            }

            $(`${selector}`).select2({
                ...select2AjaxOption,
                multiple: true,
            })
        }

        function changeImage() {
            $("body").on("change", ".image", function(e) {
                var files = e.target.files;

                if (files.length > 0) {
                    $('.image-preview').html('')
                }

                for (const file of files) {
                    let image = window.URL.createObjectURL(file)
                    $('.image-preview').append(`
                        <div>
                            <img src="${image}" style="width: 200px" class="show-image">
                        </div>
                    `)
                }
            });
        }

        function changeImageType() {
            $("#sectionType").on("change", ".image-type", function(e) {
                indexType = $(this).data('index')

                var files = e.target.files;

                if (files.length > 0) {
                    $(`.image-preview-type[data-index="${indexType}"]`).html('')
                }

                for (const file of files) {
                    let image = window.URL.createObjectURL(file)

                    $(`.image-preview-type[data-index="${indexType}"]`).append(`
                        <div>
                            <img src="${image}" style="width: 200px" class="show-image">
                        </div>
                    `)
                }
            });
        }

        function typeHtml(index) {
            return `
            <div class="input-villa-type">
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="type[${index}][name]" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Unit</label>
                                <input type="text" name="type[${index}][total_unit]" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Sewa / Malam</label>
                                <input type="text" name="type[${index}][price]" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fasilitas</label>
                                <select class="form-select select2-ajax multiple" name="type[${index}][facilities][]" data-ajax--url="{{ route('api.select2.facility') }}" data-placeholder="Fasilitas"></select>
                            </div>
                            <div class="col-12 mb-3 d-flex flex-column gap-1">
                                <label class="form-label d-block mb-0">Gambar</label>
                                <div class="d-flex flex-wrap gap-2 image-preview-type" data-index="${index}">
                                </div>
                                <label class="btn btn-sm btn-primary">
                                    <span class="d-none d-sm-block">Browse</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" class="image-type" name="type[${index}][images][]" hidden accept="image/jpeg" data-index="${index}" multiple/>
                                </label>
                                <small class="text-info">*Disarankan menggunakan gambar dengan resolusi 16:9</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea type="text" name="type[${index}][description]" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-12">
                                <select class="form-select select2" name="type[${index}][status]" data-placeholder="Status">
                                    <option></option>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `
        }
    </script>
@endsection
