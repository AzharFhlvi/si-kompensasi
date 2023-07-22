@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
use App\View\Components\ChartComponent;
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('searchbar')
<form id="searchForm" method="GET" action="{{ route('mahasiswa-search') }}">
  @csrf
    <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
            <button type="submit" class="btn p-0" aria-label="Search">
                <i class="bx bx-search fs-4 lh-0"></i>
            </button>
            <input type="text" class="form-control border-0 shadow-none" name="query" id="searchQuery" placeholder="Search..." aria-label="Search..." value="{{ request()->query('query') }}">
        </div>
    </div>
</form>
@endsection

@section('content')

<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Master Mahasiswa /</span> Daftar Mahasiswa
  <span class="text-muted fw-light" style="float: right;">{{ date('F j, Y') }}</span>
  <div style="clear: both;"></div>
</h4>

<div class="card">
  <h5 class="card-header">Daftar tanggung jawab</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Nama</th>
          <th>NIM</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      @foreach($mahasiswaList as $mahasiswa)
        <tr>
            <td>{{ $mahasiswa->nama }}</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection