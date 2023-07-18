@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Kompensasi /</span> Monitoring
  <span class="text-muted fw-light" style="float: right;"></span>
  <div style="clear: both;"></div>
</h4>

<div class="card">
  <h5 class="card-header">Kompensasi mahasiswa
        <span class="text-muted fw-light" style="float: right;">
            <form action="{{ route('kompensasi-tuntas') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">Tuntas</button>
            </form>
        </span>
        <span class="text-muted fw-light" style="float: right; margin-right: 10px;">
            <form action="{{ route('kompensasi-tolak') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
            </form>
        </span>
  </h5>
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
        @foreach($kompensasiList as $kompensasi)
        <tr>
            <td>{{ $kompensasi->mahasiswa->nama }}</td>
            <td>{{ $kompensasi->jumlah_kompensasi }} jam</td>
            <td>
                @if($kompensasi->status == 0)
                <span class="badge bg-label-warning me-1">Pending</span>
                @elseif($kompensasi->status == 1)
                <span class="badge bg-label-success me-1">Approved</span>
                @elseif($kompensasi->status == 2)
                <span class="badge bg-label-danger me-1">Rejected</span>
                @endif
            </td>
            <td>{{ $kompensasi->jadwal_kompensasi }}</td>
            <td>{{ $kompensasi->ruangan->nama_ruangan }}</td>
            <td>{{ $kompensasi->pengawas->nama }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection