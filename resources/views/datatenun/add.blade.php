<html>

<head>
    <title>Sistem Penggajian Pabrik Sarung Goyor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

</head>

<body>
    <!-- Navbar -->
    @include("header")
    <!-- /.navbar -->
    <div class="container">
        @if (Session::get('failed'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{Session::get('failed')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <h3>Tambah Data Pengerjaan Gaji Tenun</h3>
        <form action="{{ route('addViewGajiTenun') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- <div class="form-group">
                <label for="minggu" class="form-label">Minggu Ke - :</label>
                <input name="number" class="form-control" id="mingguKe" placeholder="Masukkan Minggu Ke-">
                <input type="hidden" name="karyawan_id" value="{{$karyawan_id}}" class="form-control" id="karyawan_id" placeholder="Karyawan ID">
            </div> --}}
            <div class="form-group">
                <label for="minggu" class="form-label">Minggu ke- :</label>
                <input type="number" name="minggu" class="form-control" id="minggu" required>
                <input type="hidden" name="karyawan_id" value="{{$karyawan_id}}" class="form-control" id="karyawan_id" placeholder="Karyawan ID">
            </div>
            <div class="form-group">
                <label for="tanggal" class="form-label">Tanggal :</label>
                <input name="tanggal" type="date" class="form-control" id="tanggal" value="{{date('Y-m-d')}}">
            </div>
            <div class="form-group">
                <label for="hari_1" class="form-label">Hari ke - 1 :</label>
                <input name="hari_1" type="number" class="form-control" id="hari1" value="0">
            </div>
            <div class="form-group">
                <label for="hari_2" class="form-label">Hari ke - 2 :</label>
                <input name="hari_2" type="number" class="form-control" id="hari2" value="0">
            </div>
            <div class="form-group">
                <label for="hari_3" class="form-label">Hari ke - 3 :</label>
                <input name="hari_3" type="number" class="form-control" id="hari3" value="0">
            </div>
            <div class="form-group">
                <label for="hari_4" class="form-label">Hari ke - 4 :</label>
                <input name="hari_4" type="number" class="form-control" id="hari4" value="0">
            </div>
            <div class="form-group">
                <label for="hari_5" class="form-label">Hari ke - 5 :</label>
                <input name="hari_5" type="number" class="form-control" id="hari5" value="0">
            </div>
            <div class="form-group">
                <label for="hari_6" class="form-label">Hari ke - 6 :</label>
                <input name="hari_6" type="number" class="form-control" id="hari6" value="0">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('indexGajiTenun', $karyawan_id) }}'">Batal</button>
        </form>
    </div>
</body>
<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</html>