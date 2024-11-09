<html>
<head>
    <title>Sistem Penggajian Pabrik Sarung Goyor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

</head>
<body>
    <!-- Navbar -->
    @include("header")
    <!-- /.navbar -->
    <div class="container">
        <h3>Tambah Data Karyawan</h3>
        <form>
            <div class="form-group">
                <label for="namakaryawan" class="form-label">Nama Karyawan :</label>
                <input type="text" class="form-control" id="namakaryawan" placeholder="Masukkan nama">
            </div>
            <div class="form-group">
                <label for="jabatan" class="form-label">Jabatan :</label>
                <input type="text" class="form-control" id="jabatan" placeholder="Masukkan jabatan">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ url('/datakaryawan') }}'">Batal</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>