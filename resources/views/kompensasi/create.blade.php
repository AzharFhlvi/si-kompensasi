@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<form action="{{ route('kompensasi-store') }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-lg-6">
      <div class="card mb-4">
        <h5 class="card-header">Kegiatan kompensasi</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">
            <div class="input-group">
              <span class="input-group-text" id="deskripsi">Deskripsi</span>
              <textarea class="form-control" name="deskripsi" placeholder="Enter deskripsi" aria-label="Deskripsi" aria-describedby="basic-addon1"></textarea>
            </div>

            <div class="input-group">
              <span class="input-group-text" id="jam">Jam</span>
              <input type="number" class="form-control" name="jam" placeholder="Enter jam" aria-label="Jam" aria-describedby="basic-addon2" />
            </div>

            <!-- for button -->
            <div class="input-group">
              <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</form>


@endsection