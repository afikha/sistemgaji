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
        <h3>Edit Data Pengerjaan Gaji Tenun</h3>
        <form>
            <div class="form-group">
                <label for="mingguKe" class="form-label">Minggu Ke - :</label>
                <input type="text" class="form-control" id="mingguKe" placeholder="Masukkan Minggu Ke-">
            </div>
            <div class="form-group">
                <label for="hari1" class="form-label">Hari ke - 1 :</label>
                <input type="number" class="form-control" id="hari1" value="0">
            </div>
            <div class="form-group">
                <label for="hari2" class="form-label">Hari ke - 2 :</label>
                <input type="number" class="form-control" id="hari2" value="0">
            </div>
            <div class="form-group">
                <label for="hari3" class="form-label">Hari ke - 3 :</label>
                <input type="number" class="form-control" id="hari3" value="0">
            </div>
            <div class="form-group">
                <label for="hari4" class="form-label">Hari ke - 4 :</label>
                <input type="number" class="form-control" id="hari4" value="0">
            </div>
            <div class="form-group">
                <label for="hari5" class="form-label">Hari ke - 5 :</label>
                <input type="number" class="form-control" id="hari5" value="0">
            </div>
            <div class="form-group">
                <label for="hari6" class="form-label">Hari ke - 6 :</label>
                <input type="number" class="form-control" id="hari6" value="0">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ url('/datatenun/datagajitenun') }}'">Batal</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>