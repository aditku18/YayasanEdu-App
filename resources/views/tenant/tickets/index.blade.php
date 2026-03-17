@extends('layouts.tenant-platform')

@section('title', 'Support Ticket')

@section('header', 'Support Ticket')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Ticket Support</h3>
                    <div class="card-tools">
                        <a href="{{ route('tenant.ticket.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Buat Ticket
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p>Halaman manajemen ticket support.</p>
                    <p>Fitur ini akan dikembangkan lebih lanjut untuk mengelola permintaan bantuan pengguna.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
