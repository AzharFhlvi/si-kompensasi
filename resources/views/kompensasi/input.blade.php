@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Kompensasi /</span> Input Kompensasi
  <span class="text-muted fw-light" style="float: right;">{{ date('F j, Y') }}</span>
  <div style="clear: both;"></div>
</h4>
<form action="{{ route('kompensasi-input-store') }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-lg-6">
      <div class="card mb-4">
        <h5 class="card-header d-flex align-items-center">
          Kegiatan kompensasi
          <a href="{{ url()->previous() }}" class="btn btn-link btn-sm ms-auto">
            <i class="bx bx-left-arrow-alt"></i>
          </a>
        </h5>
        <div class="card-body demo-vertical-spacing demo-only-element">
        <div class="input-group">
    <label class="input-group-text" for="jurusan">Jurusan</label>
    <select class="form-select" id="jurusan" name="jurusan">
        <option selected disabled>Choose...</option>
        @foreach($jurusan as $j)
            <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
        @endforeach
    </select>
</div>

<div class="input-group">
    <label class="input-group-text" for="prodi">Prodi</label>
    <select class="form-select" id="prodi" name="prodi" disabled>
        <option selected disabled>Choose...</option>
    </select>
</div>

<div class="input-group">
    <label class="input-group-text" for="kelas">Kelas</label>
    <select class="form-select" id="kelas" name="kelas" disabled>
        <option selected disabled>Choose...</option>
    </select>
</div>

<div class="input-group">
    <label class="input-group-text" for="pengawas">Pengawas</label>
    <select class="form-select" id="pengawas" name="pengawas">
        <option selected disabled>Choose...</option>
        @foreach($pengawas as $ps)
            <option value="{{ $ps->id }}">{{ $ps->nama }}</option>
        @endforeach
    </select>
</div>

<div class="input-group">
    <label class="input-group-text" for="ruangan">Ruangan</label>
    <select class="form-select" id="ruangan" name="ruangan">
        <option selected disabled>Choose...</option>
        @foreach($ruangan as $r)
            <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
        @endforeach
    </select>
</div>


          <div class="input-group">
            <label for="mulai_kompensasi" class="input-group-text">Mulai</label>
              <input class="form-control" name="mulai_kompensasi" type="date" value="{{ date('Y-m-d') }}" id="mulai_kompensasi" />
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
@push('jurusan-script')
<!-- make the form input to be dynamic -->
<script>
  $(document).ready(function() {
    $('#jurusan').change(function() {
      var jurusanID = $(this).val();
      if (jurusanID) {
        var prodiOptions = '';
        
        // Retrieve the prodi options for the selected jurusan from the preloaded data
        var prodiData = {!! $prodi !!};
        if (prodiData) {
          prodiOptions += '<option>Select</option>';
          $.each(prodiData, function(index, prodi) {
              if (prodi.id_jurusan == jurusanID) {
                  prodiOptions += '<option value="' + prodi.id + '">' + prodi.nama_prodi + '</option>';
              }
          });
        }

        // Set the options for the #prodi dropdown
        $("#prodi").empty();
        $("#prodi").removeAttr('disabled');
        $("#prodi").append(prodiOptions);
      } else {
        $("#prodi").empty();
        $("#kelas").empty();
      }
    });
    $('#prodi').change(function() {
        var prodiID = $(this).val();
        if (prodiID) {
            var kelasOptions = '';

            // Retrieve the kelas options for the selected prodi from the preloaded data
            var kelasData = {!! $kelas !!};
            if (kelasData) {
                kelasOptions += '<option>Select</option>';
                $.each(kelasData, function(key, value) {
                    if (value.id_prodi == prodiID) {
                        kelasOptions += '<option value="' + value.id + '">' + value.nama_kelas + '</option>';
                    }
                });
            }

            // Set the options for the #kelas dropdown
            $("#kelas").empty();
            $("#kelas").removeAttr('disabled');
            $("#kelas").append(kelasOptions);
        } else {
            $("#kelas").empty();
        }
    });
  });

  
</script>


@endpush

@endsection