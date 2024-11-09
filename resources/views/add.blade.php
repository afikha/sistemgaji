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
        <form action="{{ route('addViewKaryawan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama_karyawan" class="form-label">Nama Karyawan :</label>
                <input name="nama_karyawan" type="text" class="form-control" id="nama_karyawan" placeholder="Masukkan nama">
            </div>
            {{-- <div class="form-group">
                <label for="jabatan_karyawan" class="form-label">Jabatan :</label>
                <input name="jabatan_karyawan" type="text" class="form-control" id="jabatan_karyawan" placeholder="Masukkan jabatan">
            </div> --}}
            <div class="form-group">
                <label for="jabatan_karyawan" class="form-label">Jabatan :</label>
                <select name="jabatan_karyawan" class="form-control" id="jabatan_karyawan">
                    <option value="">Pilih Jabatan</option>
                    @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan->jabatan }}">{{ $jabatan->jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('indexKaryawan') }}'">Batal</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>