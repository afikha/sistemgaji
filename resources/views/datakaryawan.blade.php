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
        <h5>DATA KARYAWAN</h5>
        <div class="mb-3">
            <!-- <button class="btn btn-purple">+ Tambah Data</button> -->
            <a href="{{route('addViewKaryawan')}}" class="btn btn-purple">+ Tambah Data</a>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    <tr>
                        <td>{{$loop->iteration}}.</td>
                        <td>{{$d->nama_karyawan}}</td>
                        <td>{{$d->jabatan_karyawan}}</td>
                        <td class="action-icons">
                            <a><i class="fas fa-edit"></i></a>
                            <a><i class="fas fa-trash-alt"></i></a>
                            <a href="#" data-jabatan="{{ $d->jabatan_karyawan }}" class="info-link"><i class="fas fa-info-circle"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    {{-- <tr>
                        <td>1.</td>
                        <td>Agus</td>
                        <td>Tenun</td>
                        <td class="action-icons">
                            <a href="{{ url('/edit') }}"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fas fa-trash-alt"></i></a>
                            <a href="#" data-jabatan="tenun" class="info-link"><i class="fas fa-info-circle"></i></a>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
            </div>
        </div>
        <nav>
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
    <script src="script.js"></script>
</body>
</html>