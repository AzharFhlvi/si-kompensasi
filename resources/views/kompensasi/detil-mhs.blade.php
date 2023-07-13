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
  <span class="text-muted fw-light">Daftar Mahasiswa /</span> {{ $namaKelas }}
  <span class="text-muted fw-light" style="float: right;">{{ date('F j, Y') }}</span>
  <div style="clear: both;"></div>
</h4>

<div class="card">
  <h5 class="card-header d-flex align-items-center">Daftar mahasiswa {{ $namaKelas }}
  <a href="{{ url()->previous() }}" class="btn btn-link btn-sm ms-auto">
            <i class="bx bx-left-arrow-alt"></i>
          </a>
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
          <td>
            <a href="{{ route('kegiatan-mhs', $kompensasi->mahasiswa->id) }}">
                {{ $kompensasi->mahasiswa->nama }}
            </a>
          </td>
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
