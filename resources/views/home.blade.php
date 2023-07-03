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
      <h5 class="card-header">Jumlah mahasiswa kompensasi: 30</h5>
    </div>
  </div>
</div>

<div class="row">
<div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Mahasiswa kompensasi</h5>
          <small class="text-muted">42.82k Total Sales</small>
        </div>
       
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">8,258</h2>
            <span>Total Orders</span>
          </div>
          <div id="chartk"></div>

          <script>
            const chartData = @json($chartData);

            const options = {
                chart: {
                    type: 'pie',
                },
                series: chartData.map(item => item.sales),
                labels: chartData.map(item => item.year),
                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
            };

            const chart = new ApexCharts(document.querySelector("#chartk"), options);
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
          <h5 class="m-0 me-2">Bebas masalah</h5>
          <small class="text-muted">42.82k Total Sales</small>
        </div>
       
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">8,258</h2>
            <span>Total Orders</span>
          </div>
          <div id="chartku"></div>

          <script>
            const chartData2 = @json($chartData);

            const options2 = {
                chart: {
                    type: 'pie',
                },
                series: chartData.map(item => item.sales),
                labels: chartData.map(item => item.year),
                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
            };

            const chart2 = new ApexCharts(document.querySelector("#chartku"), options);
            chart2.render();
          </script>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
