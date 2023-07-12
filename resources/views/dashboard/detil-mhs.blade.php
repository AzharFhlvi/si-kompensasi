@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
use App\View\Components\ChartComponent;
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Menu Utama /</span> Dashboard
  <span class="text-muted fw-light" style="float: right;">{{ date('F j, Y') }}</span>
  <div style="clear: both;"></div>
</h4>

<div class="card">
  <h5 class="card-header">Kompensasi mahasiswa</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Mahasiswa</th>
          <th>Kompensasi</th>
          <th>Status</th>
          <th>Deadline</th>
          <th>Ruangan</th>
          <th>Pengawas</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($kompensasi as $kom)
        <tr>
            <td>{{ $kom->mahasiswa->nama }}</td>
            <td>{{ $kom->jumlah_kompensasi }} jam</td>
            <td>
                @if($kom->status == 0)
                <span class="badge bg-label-warning me-1">Pending</span>
                @elseif($kom->status == 1)
                <span class="badge bg-label-success me-1">Approved</span>
                @elseif($kom->status == 2)
                <span class="badge bg-label-danger me-1">Rejected</span>
                @endif
            </td>
            <td>{{ $kom->jadwal_kompensasi }}</td>
            <td>{{ $kom->ruangan->nama_ruangan }}</td>
            <td>{{ $kom->pengawas->nama }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection
