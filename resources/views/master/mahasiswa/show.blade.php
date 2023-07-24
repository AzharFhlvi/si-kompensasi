@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
use App\View\Components\ChartComponent;
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<form action="{{ route('mahasiswa-update', ['id'=>$mahasiswa->id]) }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <h5 class="card-header d-flex align-items-center">
                    Tambah Mahasiswa
                    <a href="{{ url()->previous() }}" class="btn btn-link btn-sm ms-auto">
                        <i class="bx bx-left-arrow-alt"></i>
                    </a>
                    </h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="input-group">
                        <span class="input-group-text" id="nim">NIM</span>
                        <input type="text" class="form-control" name="nim" value="{{ $mahasiswa->nim }}" placeholder="Enter nim" aria-label="NIM" aria-describedby="basic-addon1" />
                        </div>
                        <div class="input-group">
                        <span class="input-group-text" id="nama">Nama</span>
                        <input type="text" class="form-control" name="nama" value="{{ $mahasiswa->nama }}" placeholder="Enter nama" aria-label="Nama" aria-describedby="basic-addon1" />
                        </div>
                        <div class="input-group">
                        <span class="input-group-text" id="alamat">Alamat</span>
                        <textarea name="alamat" class="form-control" placeholder="Enter alamat">{{ $mahasiswa->alamat }}</textarea>
                        </div>
                        <div class="input-group">
                        <span class="input-group-text" id="tanggal_lahir">Tanggal Lahir</span>
                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ $mahasiswa->tanggal_lahir }}" aria-label="Tanggal Lahir" aria-describedby="basic-addon1" />
                        </div>
                        <div class="input-group">
                        <span class="input-group-text" id="no_hp">No HP</span>
                        <input type="text" class="form-control" name="no_hp" value="{{ $mahasiswa->no_hp }}" placeholder="Enter no hp" aria-label="No HP" aria-describedby="basic-addon1" />
                        </div>
                        <div class="input-group">
                        <span class="input-group-text" id="jenis_kelamin">Jenis Kelamin</span>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L" {{ $mahasiswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $mahasiswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="input-group">
                <label class="input-group-text" for="jurusan">Jurusan</label>
                <select class="form-select" id="jurusan" name="jurusan">
                    <option selected >Choose...</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id }}" {{ $j->id == $jurusanMhs->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label class="input-group-text" for="prodi">Prodi</label>
                <select class="form-select" id="prodi" name="prodi">
                    <option selected >Choose...</option>
                    @foreach($prodi as $p)
                        <option value="{{ $p->id }}" {{ $p->id == $prodiMhs->id ? 'selected' : '' }}>
                            {{ $p->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label class="input-group-text" for="kelas">Kelas</label>
                <select class="form-select" id="kelas" name="kelas">
                    <option selected >Choose...</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ $k->id == $kelasMhs->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

                        <div class="input-group">
                            <label class="input-group-text" for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" class="form-control" id="tahun_ajaran_start" name="tahun_ajaran_start" value="{{ explode('-', $mahasiswa->tahun_ajaran)[0] }}" placeholder="(e.g. 2022)">
                            <input type="text" class="form-control" id="tahun_ajaran_end" name="tahun_ajaran_end" value="{{ explode('-', $mahasiswa->tahun_ajaran)[1] }}" placeholder="(e.g. 2023)">
                        </div>

                        
                        
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
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