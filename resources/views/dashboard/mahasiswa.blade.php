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

<div class="row">
  <div class="col-lg-8 mb-4 order-1">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $sumAbsen }} pertemuan</h2>
            <span>Total pertemuan tidak hadir</span>
          </div>
          <div id="chartk"></div>

          <script>
            const chartData = @json($chartData);

            const options = {
                chart: {
                    type: 'pie',
                },
                series: chartData.map(item => item.value),
                labels: chartData.map(item => item.type),
                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
            };

            const chart = new ApexCharts(document.querySelector("#chartk"), options);
            chart.render();
          </script>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 order-0">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <span class="fw-semibold d-block mb-1">Total jam kompensasi</span>
            <h3 class="card-title mb-2">{{ $kompensasi->jumlah_kompensasi }} jam</h3>
          </div>
        </div>
      </div>
      @if($kompensasi->status == 1)
      <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <span class="fw-semibold d-block mb-1">Download Surat</span>
            <h3 class="card-title mb-2">
              <a href="{{ route('kompensasi-download-surat', $kompensasi->id) }}" class="btn btn-primary btn-sm">Download</a>
            </h3>
          </div>
        </div>
      </div>
      @endif
      <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <span class="fw-semibold d-block mb-1">Status kompensasi</span>
            <h3 class="card-title mb-2">
            @if ($kompensasi->status == 0)
                On Going
            @elseif ($kompensasi->status == 1)
                Tuntas
            @elseif ($kompensasi->status == 2)
                Tidak Tuntas
            @endif
            </h3>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <span class="fw-semibold d-block mb-1">Deadline kompensasi</span>
            <h3 class="card-title mb-2">
            {{ \Carbon\Carbon::parse($kompensasi->jadwal_kompensasi)->format('d-m-Y') }}
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection
