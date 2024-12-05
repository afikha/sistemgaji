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
        <h3>Edit Data Upah Karyawan</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('updateUpah') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input name="id" type="hidden" value="{{$data->id}}">
            <div class="form-group">
                <label for="jabatan" class="form-label">Jabatan :</label>
                <input name="jabatan" class="form-control" id="jabatan" placeholder="Masukkan jabatan" value="{{$data->jabatan}}">
            </div>
            <div class="form-group">
                <label for="upah" class="form-label">Upah :</label>
                <input name="upah" class="form-control" id="upah" placeholder="Masukkan upah" value="{{$data->upah}}">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('indexUpah') }}'">Batal</button>
        </form>
    </div>
</body>
</html>