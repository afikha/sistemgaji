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
        <h3>Tambah Data Pengerjaan Gaji Barang</h3>
        <form>
            <div class="form-group">
                <label for="tanggal" class="form-label">Tanggal :</label>
                <input type="date" class="form-control" id="tanggal">
            </div>
            <div class="form-group">
                <label for="barangmasuk" class="form-label">Barang Masuk :</label>
                <input type="number" class="form-control" id="barangmasuk" value="0">
            </div>
            <div class="form-group">
                <label for="barangkeluar" class="form-label">Barang Keluar :</label>
                <input type="number" class="form-control" id="barangkeluar" value="0">
            </div>
            <div class="form-group">
                <label for="sisabahan" class="form-label">Sisa Bahan :</label>
                <input type="number" class="form-control" id="sisabahan" value="0">
            </div>
            <div class="form-group">
                <label for="totaldikerjakan" class="form-label">Total Dikerjakan :</label>
                <input type="number" class="form-control" id="totaldikerjakan" value="0">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ url('/databarang/datagajibarang') }}'">Batal</button>
        </form>
    </div>
    <script src="scripts.js"></script>
</body>
</html>