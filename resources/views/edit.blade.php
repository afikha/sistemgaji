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
        <h3>Edit Data Karyawan</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('updateKaryawan')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input name="id" type="hidden" value="{{$data->id}}">
            <div class="form-group">
                <label for="nama_karyawan" class="form-label">Nama Karyawan :</label>
                <input name="nama_karyawan" type="text" class="form-control" id="nama_karyawan" placeholder="Masukkan nama" value="{{$data->nama_karyawan}}">
            </div>
            <div class="form-group">
                <label for="NIK" class="form-label">NIK Karyawan :</label>
                <input name="NIK" type="text" class="form-control" id="NIK" maxlength="16" placeholder="Masukkan NIK" value="{{$data->NIK}}">
            </div>
            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Karyawan :</label>
                <input name="alamat" type="text" class="form-control" id="alamat" placeholder="Masukkan alamat" value="{{$data->alamat}}">
            </div>
            <div class="form-group">
                <label for="jabatan_karyawan" class="form-label">Jabatan :</label>
                <select name="jabatan_karyawan" class="form-control" id="jabatan_karyawan">
                    <option value="">Pilih Jabatan</option>
                    @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan->jabatan }}" {{ $jabatan->jabatan == $data->jabatan_karyawan ? 'selected' : '' }}>
                            {{ $jabatan->jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('indexKaryawan') }}'">Batal</button>
        </form>
    </div>
    <script src="script.js"></script>
    <script>
        document.getElementById('nama_karyawan').addEventListener('input', function (event) {
            const input = event.target;
            const value = input.value;
            // Hanya izinkan huruf, spasi, dan tanda baca dasar
            const filteredValue = value.replace(/[^a-zA-Z\s]/g, '');
            if (value !== filteredValue) {
                input.value = filteredValue;
            }
        });
    </script>    
</body>
</html>