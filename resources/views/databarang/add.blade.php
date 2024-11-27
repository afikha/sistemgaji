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
        <h3>Tambah Data Pengerjaan Gaji Barang</h3>
        <form action="{{route('addGajiBarang')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tanggal" class="form-label">Tanggal :</label>
                <input type="date" name="tanggal" class="form-control" id="tanggal">
                <input type="hidden" name="karyawan_id" value="{{$karyawan_id}}" class="form-control" id="karyawan_id" placeholder="Karyawan ID">
            </div>
            <div class="form-group">
                <label for="minggu" class="form-label">Minggu ke- :</label>
                <input type="number" name="minggu" class="form-control" id="minggu" required>
            </div>
            <div class="form-group">
                <label for="barangmasuk" class="form-label">Barang Masuk :</label>
                <input type="number" name="barangmasuk" class="form-control" id="barangmasuk" value="0">
            </div>
            <div class="form-group">
                <label for="barangkeluar" class="form-label">Barang Keluar :</label>
                <input type="number" name="barangkeluar" class="form-control" id="barangkeluar" value="0">
            </div>
            <div class="form-group">
                <label for="sisabahan" class="form-label">Sisa Proses Sebelumnya :</label>
                <input type="number" name="sisabahan" class="form-control" id="sisabahan" value="0">
            </div>
            <div class="form-group">
                <label for="totaldikerjakan" class="form-label">Total Dikerjakan :</label>
                <input type="number" name="totaldikerjakan" class="form-control" id="totaldikerjakan" value="0">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{  route('indexGajiBarang', $karyawan_id)}}'">Batal</button>
        </form>
    </div>

</body>
<script src="scripts.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</html>