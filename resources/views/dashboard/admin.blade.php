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
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <h5 class="card-header">Jumlah mahasiswa kompensasi: {{ $total_kompensasi }}</h5>
    </div>
  </div>
</div>

<div class="row">
<div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">On going</h5>
        </div>
       
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $onGoingTotal }}</h2>
            <span>Total mahasiswa</span>
          </div>
          <div id="chartOnGoing"></div>

          <script>
            const chartDataOnGoing = @json($chartDataOnGoing);

            const onGoingOptions = {
                chart: {
                    type: 'pie',
                },
                series: chartDataOnGoing.map(item => item.value),
                labels: chartDataOnGoing.map(item => item.type),
                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
            };

            const chart = new ApexCharts(document.querySelector("#chartOnGoing"), onGoingOptions);
            chart.render();
          </script>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Tuntas</h5>
        </div>
       
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $tuntasTotal }}</h2>
            <span>Total mahasiswa</span>
          </div>
          <div id="chartTuntas"></div>

          <script>
            const chartTuntas = @json($chartDataTuntas);

            const tuntasOptions = {
                chart: {
                    type: 'pie',
                },
                series: chartTuntas.map(item => item.value),
                labels: chartTuntas.map(item => item.type),
                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
            };

            const chart2 = new ApexCharts(document.querySelector("#chartTuntas"), tuntasOptions);
            chart2.render();
          </script>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Tidak tuntas</h5>
        </div>
       
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $tidakTuntasTotal }}</h2>
            <span>Total mahasiswa</span>
          </div>
          <div id="chartTidakTuntas"></div>

          <script>
            const chartTidakTuntas = @json($chartDataTidakTuntas);

            const tidakTuntasOptions = {
                chart: {
                    type: 'pie',
                },
                series: chartTidakTuntas.map(item => item.value),
                labels: chartTidakTuntas.map(item => item.type),
                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
            };

            const chart3 = new ApexCharts(document.querySelector("#chartTidakTuntas"), tidakTuntasOptions);
            chart3.render();
          </script>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
