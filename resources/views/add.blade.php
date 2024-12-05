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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('addViewKaryawan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama_karyawan" class="form-label">Nama Karyawan :</label>
                <input name="nama_karyawan" type="text" class="form-control" id="nama_karyawan" placeholder="Masukkan nama" required oninput="validateName(event)">

            </div>
            <div class="form-group">
                <label for="NIK" class="form-label">NIK Karyawan :</label>
                <input name="NIK" type="text" class="form-control" id="NIK" placeholder="Masukkan NIK">
            </div>
            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Karyawan :</label>
                <input name="alamat" type="text" class="form-control" id="alamat" placeholder="Masukkan alamat">
            </div>
            <div class="form-group">
                <label for="jabatan_karyawan" class="form-label">Jobdesk :</label>
                <select name="jabatan_karyawan" class="form-control" id="jabatan_karyawan">
                    <option value="">Pilih Jobdesk</option>
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
    <script>
        function validateName(event) {
            const input = event.target; // Mendapatkan elemen input
            const errorMessage = document.getElementById("nameError");

            // Menghapus karakter non-huruf
            input.value = input.value.replace(/[^a-zA-Z\s]/g, '');

            // Menampilkan pesan kesalahan jika input tidak hanya huruf
            if (/[^a-zA-Z\s]/.test(input.value)) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        }
    </script>
</body>
</html>