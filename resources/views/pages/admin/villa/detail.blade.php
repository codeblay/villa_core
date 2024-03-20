@extends('layouts.admin.index')
@section('title', 'Detail')

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="carouselExample" class="carousel slide " data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach ($villa->files_path as $path)
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($villa->files_path as $path)
                        <div class="carousel-item text-center {{ $loop->first ? 'active' : '' }}" >
                            <img src="{{ $path }}" style="height: 400px">
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                    <i class="text-secondary menu-icon tf-icons bx bx-left-arrow-alt"></i>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                    <i class="text-secondary menu-icon tf-icons bx bx-right-arrow-alt"></i>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>
        <div class="card-body border-top">
            <h3>{{ $villa->name }}</h3>
            <h6 class="mb-1">{{ $villa->seller->name }}</h5>
                <div class="w-100 d-flex justify-content-between card-text mb-3">
                    <div>
                        <span
                            class="badge bg-label-{{ $villa->is_publish ? 'primary' : 'secondary' }}">{{ $villa->publish_label }}</span>
                        <span class="badge bg-label-danger">{{ $villa->city->address }}</span>
                    </div>
                    <div>
                        <span class="badge bg-label-success">{{ rupiah($villa->price) }}</span>
                    </div>
                </div>
                <p class="card-text">
                    {{ $villa->description }}
                </p>
        </div>
    </div>
@endsection
