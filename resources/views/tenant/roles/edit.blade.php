@extends('layouts.tenant-platform')

@section('title', 'Edit Role')

@section('header', 'Edit Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Role {{ $id ?? '' }}</h3>
                </div>
                <div class="card-body">
                    <p>Halaman edit role.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
