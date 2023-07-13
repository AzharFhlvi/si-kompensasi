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
  <span class="text-muted fw-light">Kegiatan / {{ $mahasiswa->nama }}</span> 
  <span class="text-muted fw-light" style="float: right;">{{ date('F j, Y') }}</span>
  <div style="clear: both;"></div>
</h4>

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <h5 class="card-header"> 
      {{ $mahasiswa->nama }} / 
        <span class="badge bg-label-primary">{{ $kompensasi->jumlah_kompensasi }} Jam</span>
        @if($kompensasi->status == 0)
          <span class="badge bg-label-warning">Pending</span>
        @elseif($kompensasi->status == 1)
          <span class="badge bg-label-success">Approved</span>
        @elseif($kompensasi->status == 2)
          <span class="badge bg-label-danger">Rejected</span>
        @endif
        <span class="text-muted fw-light" style="float: right;">{{ $kompensasi->jadwal_kompensasi }}</span>
      </h5>
    </div>
  </div>
</div>

<div class="row">
<div class="col-lg-12 mb-4 order-0">
<div class="card">
  <h5 class="card-header d-flex align-items-center">Daftar mahasiswa 
  <a class="btn btn-link btn-sm ms-auto" href="{{ route('kompensasi-pengawas-detil-kelas', $kompensasi->mahasiswa->id_kelas) }}">
            <i class="bx bx-left-arrow-alt"></i>
          </a>
  </h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th>Deskripsi</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($kegiatanList as $kegiatan)
            <form action="{{ route('kegiatan-store', $kegiatan->id) }}" method="POST">
                @csrf
                <tr>
                    <td>{{ $kegiatan->deskripsi }} jam</td>
                    <td>
                        <input type="text" value="{{ $kegiatan->jam }}" name="jam">
                        
                    </td>
                    <td>
                        @if($kegiatan->status == 0)
                        <span class="badge bg-label-warning me-1">Pending</span>
                        @elseif($kegiatan->status == 1)
                        <span class="badge bg-label-success me-1">Approved</span>
                        @elseif($kegiatan->status == 2)
                        <span class="badge bg-label-danger me-1">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($kegiatan->status == 0)
                        <button type="submit" class="btn rounded-pill btn-sm btn-icon btn-primary" name="approve">
                            <span class="tf-icons bx bx-check"></span>
                        </button>
                        <button type="submit" class="btn rounded-pill btn-sm btn-icon btn-danger" name="reject">
                            <span class="tf-icons bx bx-x"></span>
                        </button>
                        @endif
                    </td>
                    
                </tr>
            </form>
            @endforeach
        </tbody>
        </table>
    </div>
    </div>
</div>
</div>
</div>
@endsection
