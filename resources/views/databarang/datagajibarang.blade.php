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
        <div class="d-flex align-items-center">
            <h5>GAJI BARANG</h5>
        </div>
        <div class="mb-3">
            <a href="{{ url('/databarang/add') }}" class="btn btn-purple">+ Tambah Data</a>
            <select class="btn btn-outline-secondary">
                <option>Minggu Periode</option>
            </select>
            <button class="btn btn-green">Generate Form</button>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang Jadi</th>
                            <th>Barang Proses</th>
                            <th>Sisa Bahan Proses</th>
                            <th>Total Pengerjaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10/02/2024</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>6</td>
                            <td class="action-icons">
                                <a href="{{ url('/databarang/edit') }}"><i class="fas fa-edit"></i></a>
                                <a href="#"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="total-gaji-wrapper">
            <div class="total-gaji-container">
                <div class="total-gaji">
                    Total Gaji = <span id="total-gaji">0</span>
                </div>
                <button class="btn-calculate">HITUNG</button>
            </div>
        </div>
    </div>
    
    <script src="scripts.js"></script>
</body>
</html>