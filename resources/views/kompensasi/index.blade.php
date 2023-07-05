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
  <h5 class="card-header">Pak Ruslan | Perpustakaan
    <span class="text-muted fw-light" style="float: right;">Kompensasi : 100 jam</span>
  </h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Tanggal</th>
          <th>Kegiatan</th>
          <th>Waktu</th>
          <th>Jam kompensasi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <tr>
          <td>1</td>
          <td>{{ date('D, j-F-Y') }}</td>
          <td>
            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
              Bersih-bersih
            </ul>
          </td>
          <td>09.00 - 12.00</td>
          <td>
            3 jam
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>{{ date('D, j-F-Y') }}</td>
          <td>
            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
              Bersih-bersih
            </ul>
          </td>
          <td>09.00 - 12.00</td>
          <td>
            3 jam
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>{{ date('D, j-F-Y') }}</td>
          <td>
            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
              Bersih-bersih
            </ul>
          </td>
          <td>09.00 - 12.00</td>
          <td>
            3 jam
          </td>
        </tr>
        <tr>
          <td>4</td>
          <td>{{ date('D, j-F-Y') }}</td>
          <td>
            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
              Bersih-bersih
            </ul>
          </td>
          <td>09.00 - 12.00</td>
          <td>
            3 jam
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    <a href="{{ route('kompensasi-create') }}" class="btn btn-primary">Tambah Kompensasi</a>
  </div>
</div>

@endsection