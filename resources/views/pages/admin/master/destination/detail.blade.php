@extends('layouts.admin.index')
@section('title', 'Destinasi')

@section('action')
    <form action="{{ route('admin.master.destination.list.edit', $destination->id) }}">
        <button class="btn btn-warning btn-sm">Edit</button>
    </form>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-center" style="height: 400px">
                <img class=""object-fit-contain" src="{{ asset($destination->image_path) }}">
            </div>
        </div>
        <div class="card-body border-top">
            <h3>{{ $destination->name }}</h3>
            <p class="card-text">
                <span class="badge bg-label-info">{{ $destination->category->name }}</span>
                <span class="badge bg-label-danger">{{ $destination->city->address }}</span>
            </p>
            <p class="card-text">
                {{ $destination->description }}
            </p>
        </div>
    </div>
@endsection
