@extends('layouts.admin.index')
@section('title', 'Agen')

@section('content')
    <form action="{{ route('admin.system.security.store') }}" method="POST">
        @csrf
        <div class="card">
            <h5 class="card-header">Password</h5>
            <hr class="my-0">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="password_old" class="form-label">Password Lama</label>
                        <input type="password" id="password_old" class="form-control" name="password_old"
                            placeholder="Masukkan password lama">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="password_new" class="form-label">Password Baru</label>
                        <input type="password" id="password_new" class="form-control" name="password_new"
                            placeholder="Masukkan password baru">
                    </div>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-body d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>
    </form>
@endsection
