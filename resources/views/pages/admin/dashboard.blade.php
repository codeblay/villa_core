@extends('layouts.admin.index')
@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Transaksi</h5>
                </div>
                <div class="card-body">
                    <ul class="d-flex flex-column p-0 m-0 gap-3">
                        @foreach ($transaction as $v)
                            <li class="d-flex">
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">
                                            <span
                                                class="badge bg-{{ $v['class'] }}">{{ $v['label'] }}</span>
                                        </h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-1">
                                        <h6 class="mb-0">{{ $v['value'] }}</h6>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
