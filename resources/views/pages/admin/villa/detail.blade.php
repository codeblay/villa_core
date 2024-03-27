@extends('layouts.admin.index')
@section('title', 'Villa')

@section('content')
    <div class="row">
        <div class="col col-12 col-lg-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <span>Pemilik</span>
                            <br>
                            <span class="badge bg-label-info">{{ $villa->seller->name }}</span>
                        </div>
                        <div>
                            <span>Lokasi</span>
                            <br>
                            <span class="badge bg-label-danger">{{ $villa->city->address }}</span>
                        </div>
                        <div>
                            <span>Harga</span>
                            <br>
                            <span class="badge bg-label-success">{{ rupiah($villa->price) }}</span>
                        </div>
                        <div>
                            <span>Status</span>
                            <br>
                            <span
                                class="badge bg-label-{{ $villa->is_publish ? 'primary' : 'secondary' }}">{{ $villa->publish_label }}</span>
                        </div>
                        <div>
                            <span>Fasilitas</span>
                            <br>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($villa->facilities as $facility)
                                    <span class="badge bg-label-secondary">{{ $facility->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div id="carouselExample" class="carousel slide " data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach ($villa->files as $file)
                                <button type="button" data-bs-target="#carouselExample"
                                    data-bs-slide-to="{{ $loop->index }}"
                                    class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach ($villa->files as $file)
                                <div class="carousel-item text-center {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ $file->local_path }}" style="height: 400px">
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
                    <p class="card-text">
                        {{ $villa->description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
