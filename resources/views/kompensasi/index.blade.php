@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Menu Utama /</span> Kompensasi
  <div style="clear: both;"></div>
</h4>

<div class="card">
  <h5 class="card-header">{{ $kompensasi->pengawas->nama }} | {{ $kompensasi->ruangan->nama_ruangan }}
    <span class="text-muted fw-light" style="float: right;">Kompensasi : {{$kompensasi->jumlah_kompensasi}} jam</span>
  </h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Tanggal</th>
          <th>Kegiatan</th>
          <th>Lama kegiatan</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($kegiatanList as $kegiatan)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $kegiatan->created_at->format('D, j F Y') }}</td>
          <td>
            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
              {{$kegiatan->deskripsi}}
            </ul>
          </td>
          <td>
            3 jam
          </td>
          <td>
            @if($kegiatan->status == 0)
            <span class="badge bg-warning">Menunggu</span>
            @elseif($kegiatan->status == 1)
            <span class="badge bg-success">Disetujui</span>
            @elseif($kegiatan->status == 2)
            <span class="badge bg-danger">Ditolak</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    <a href="{{ route('kompensasi-create') }}" class="btn btn-primary">Tambah Kompensasi</a>
  </div>
</div>

@endsection