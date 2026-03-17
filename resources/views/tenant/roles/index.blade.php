@extends('layouts.tenant-platform')

@section('title', 'Manajemen Role')

@section('header', 'Manajemen Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Role</h3>
                    <div class="card-tools">
                        <a href="{{ route('tenant.role.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Role
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p>Halaman manajemen role pengguna sistem.</p>
                    <p>Fitur ini akan dikembangkan lebih lanjut.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
