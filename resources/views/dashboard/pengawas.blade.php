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
  <h5 class="card-header">Daftar tanggung jawab</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Kelas</th>
          <th>Jadwal</th>
          <th>Ruangan</th>
          <th>Pengawas</th>
          <th>Kompensasi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      @foreach($kompensasis as $kelas => $kompensasiGroup)
        @php
            $firstKompensasi = $kompensasiGroup->first();
            $kompensasiCount = $kompensasiGroup->count();
        @endphp
        <tr>
            <td>
              <a href="{{ route('kompensasi-pengawas-detil-kelas', $firstKompensasi->mahasiswa->kelas->id) }}">
                  {{ $firstKompensasi->mahasiswa->kelas->nama_kelas }}
              </a>
            </td>
            <td>{{ $firstKompensasi->mulai_kompensasi }} jam</td>
            <td>{{ optional($firstKompensasi->ruangan)->nama_ruangan }}</td>
            <td>{{ optional($firstKompensasi->pengawas)->nama }}</td>
            <td>{{ $kompensasiCount }} Mahasiswa</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection
