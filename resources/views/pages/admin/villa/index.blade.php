@extends('layouts.admin.index')
@section('title', 'Villa')

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
                        <select class="form-select select2-ajax" name="seller_id"
                            data-ajax--url="{{ route('api.select2.seller') }}" data-placeholder="Pemilik">
                        </select>
                    </div>
                    <div style="flex-grow: 1">
                        <select class="form-select select2" name="status" data-placeholder="Status">
                            <option></option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
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
                        <th>Pemilik</th>
                        <th>Transaki</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($villas as $villa)
                        <tr>
                            <td><span class="fw-medium">{{ $villa->name }}</span></td>
                            <td>{{ $villa->city->address }}</td>
                            <td>{{ $villa->seller->name }}</td>
                            <td>{{ $villa->transactions_success_count }}</td>
                            <td><span
                                    class="badge bg-label-{{ $villa->is_publish ? 'primary' : 'secondary' }} me-1">{{ $villa->publish_label }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-icon btn-primary btn-sm detail-button"
                                    data-bs-toggle="tooltip" data-bs-original-title="Detail"
                                    data-url="{{ route('admin.villa.detail', $villa->id) }}">
                                    <span class="tf-icons bx bx-show"></span>
                                </button>
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

    @if ($villas->hasPages())
        <div class="card mt-2 pt-3 pe-3 align-items-end justify-content-center">
            {{ $villas->links() }}
        </div>
    @endif

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

    @if (request('seller_id'))
        <script>
            $(function() {
                $.ajax({
                    type: "GET",
                    url: `{{ route('api.select2.seller.detail', request('seller_id')) }}`,
                    dataType: "json",
                    success: function(response) {
                        $(`[name="seller_id"]`).append(
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
    </script>
@endsection
