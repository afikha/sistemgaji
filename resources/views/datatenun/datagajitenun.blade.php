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
    <a href="{{ url('/datakaryawan') }}" class="btn btn-yellow mr-3">Kembali</a> <!-- Move button to the left -->
    <div class="container">
        <h5>GAJI TENUN</h5>
        <div class="mb-3">
            <!-- <button class="btn btn-purple">+ Tambah Data</button> -->
            <a href="{{ url('/datatenun/add') }}" class="btn btn-purple">+ Tambah Data</a>
            <button class="btn btn-purple">THR</button>
            <select class="btn btn-outline-secondary">
                <option>Tahun Periode</option>
            </select>
            <button class="btn btn-green">Generate Form</button>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Minggu Ke-</th>
                            <th>H1</th>
                            <th>H1</th>
                            <th>H1</th>
                            <th>H1</th>
                            <th>H1</th>
                            <th>H1</th>
                            <th>Total Pengerjaan</th>
                            <th>Gaji</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>6</td>
                            <td>Rp.150.000</td>
                            <td class="action-icons">
                                <a href="#"><i class="fas fa-redo-alt"></i></a>
                                <a href="{{ url('/datatenun/edit') }}"><i class="fas fa-edit"></i></a>
                                <a href="#"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
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
    <script src="script2.js"></script>
</body>
</html>