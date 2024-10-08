@extends('layouts.admin.index')
@section('title', 'Villa')

@section('action')
    <a href="{{ route('admin.villa.create') }}" class="btn btn-success btn-sm">Tambah</a>
@endsection

@section('content')
    <div class="card mb-2">
        <form action="">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-2">
                    <div style="flex-grow: 1">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                placeholder="Cari villa..." aria-label="Cari villa..."
                                aria-describedby="basic-addon-search31" />
                        </div>
                    </div>
                    <div style="flex-grow: 1">
                        <select class="form-select select2-ajax" name="city_id"
                            data-ajax--url="{{ route('api.select2.location') }}" data-placeholder="Lokasi">
                        </select>
                    </div>
                    <div style="flex-grow: 1">
                        <select class="form-select select2" name="status" data-placeholder="Status">
                            <option></option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div style="flex-grow: 1">
                        <select class="form-select select2" name="rating" data-placeholder="Rating">
                            <option></option>
                            @for ($i = 1; $i < 6; $i++)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    @for ($j = $i; $j > 0; $j--)
                                        ⭐
                                    @endfor
                                </option>
                            @endfor
                        </select>
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
            <table class="table {{ count($villas) == 0 ? 'table' : 'table-hover' }}">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Investor</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($villas as $villa)
                        <tr>
                            <td><span class="fw-medium">{{ $villa->name }}</span></td>
                            <td>{{ $villa->city->address }}</td>
                            <td>{{ $villa->investors_count }}</td>
                            <td>{{ $villa->rating }} ⭐</td>
                            <td><span
                                    class="badge bg-label-{{ $villa->is_publish ? 'primary' : 'secondary' }} me-1">{{ $villa->publish_label }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-icon btn-warning btn-sm rating-button"
                                    data-bs-toggle="tooltip" data-bs-original-title="Rating"
                                    data-name="{{ $villa->name }}" data-rating="{{ $villa->rating }}"
                                    data-url="{{ route('admin.villa.bypass-rating', $villa->id) }}">
                                    <span class="tf-icons bx bx-star"></span>
                                </button>
                                <button type="button" class="btn btn-icon btn-info btn-sm investor-button"
                                    data-bs-toggle="tooltip" data-bs-original-title="Investor"
                                    data-url="{{ route('admin.villa.investor', $villa->id) }}">
                                    <span class="tf-icons bx bx-user"></span>
                                </button>
                                <button type="button" class="btn btn-icon btn-primary btn-sm detail-button"
                                    data-bs-toggle="tooltip" data-bs-original-title="Detail"
                                    data-url="{{ route('admin.villa.detail', $villa->id) }}">
                                    <span class="tf-icons bx bx-show"></span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="7">@include('components.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($villas->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $villas->links() }}
        </div>
    @endif

    <div class="modal fade" id="modalRating" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRatingTitle">Rating (Bypass)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <span class="badge bg-primary" id="name"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="rating">Rating</label>
                            <select class="form-select select2" name="rate" data-placeholder="Rating" id="rating">
                                <option></option>
                                @for ($i = 1; $i < 6; $i++)
                                    <option value="{{ $i }}">
                                        @for ($j = $i; $j > 0; $j--)
                                            ⭐
                                        @endfor
                                    </option>
                                @endfor
                            </select>
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
    @if (request('city_id'))
        <script>
            $(function() {
                $.ajax({
                    type: "GET",
                    url: `{{ route('api.select2.location.detail', request('city_id')) }}`,
                    dataType: "json",
                    success: function(response) {
                        $(`[name="city_id"]`).append(
                            `<option value="${response.id}">${response.text}</option>`)
                    }
                });
            })
        </script>
    @endif

    <script>
        $('.detail-button').click(function(e) {
            e.preventDefault()
            window.location.href = $(this).data('url')
        })

        $('.investor-button').click(function(e) {
            e.preventDefault()
            window.location.href = $(this).data('url')
        })

        $('.rating-button').click(function(e) {
            e.preventDefault()

            let name = $(this).data('name')
            let rating = $(this).data('rating')
            let url = $(this).data('url')

            $('#modalRating [name="rate"]').val(rating).trigger('change')

            $('#modalRating #name').text(name)
            $('#modalRating form').attr('action', url)
            $('#modalRating').modal('show')
        })
    </script>
@endsection
