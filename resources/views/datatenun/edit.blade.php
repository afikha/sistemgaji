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
        @if (Session::get('failed'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{Session::get('failed')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <h3>Edit Data Pengerjaan Gaji Tenun</h3>
        <form action="{{ route('updateGajiTenun') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}"> <!-- Menambahkan ID untuk update -->
            <input type="hidden" name="karyawan_id" value="{{ $data->karyawan_id }}">
            <div class="form-group">
                <label for="minggu" class="form-label">Minggu Ke - :</label>
                <input type="number" name="minggu" class="form-control" id="mingguKe" placeholder="Masukkan Minggu Ke-" value="{{$data->minggu}}">
            </div>
            <div class="form-group">
                <label for="tanggal" class="form-label">Tanggal :</label>
                <input name="tanggal" type="date" class="form-control" id="tanggal" value="{{$data->tanggal}}">
            </div>
            <div class="form-group">
                <label for="hari_1" class="form-label">Hari ke - 1 :</label>
                <input name="hari_1" type="number" class="form-control" id="hari1" value="{{$data->hari_1}}">
            </div>
            <div class="form-group">
                <label for="hari_2" class="form-label">Hari ke - 2 :</label>
                <input name="hari_2" type="number" class="form-control" id="hari2" value="{{$data->hari_2}}">
            </div>
            <div class="form-group">
                <label for="hari_3" class="form-label">Hari ke - 3 :</label>
                <input name="hari_3" type="number" class="form-control" id="hari3" value="{{$data->hari_3}}">
            </div>
            <div class="form-group">
                <label for="hari_4" class="form-label">Hari ke - 4 :</label>
                <input name="hari_4" type="number" class="form-control" id="hari4" value="{{$data->hari_4}}">
            </div>
            <div class="form-group">
                <label for="hari_5" class="form-label">Hari ke - 5 :</label>
                <input name="hari_5" type="number" class="form-control" id="hari5" value="{{$data->hari_5}}">
            </div>
            <div class="form-group">
                <label for="hari_6" class="form-label">Hari ke - 6 :</label>
                <input name="hari_6" type="number" class="form-control" id="hari6" value="{{$data->hari_6}}">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('indexGajiTenun', $data->karyawan_id) }}'">Batal</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>