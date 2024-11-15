<html>
<head>
    <title>Sistem Penggajian Pabrik Sarung Goyor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

     <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin-lte') }}/dist/css/adminlte.min.css">

</head>
<body>
    <!-- Navbar -->
    @include("header")
    <!-- /.navbar -->
    <a href="{{ url('/datakaryawan') }}" class="btn btn-yellow mr-3">Kembali</a> <!-- Move button to the left -->
    <div class="container">
        {{-- <div class="d-flex align-items-center">
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
        </div> --}}

        <h5>GAJI BARANG</h5>
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">Filter Data Gaji Barang</div>
            <div class="card-body">
                <form class="form-inline" method="GET" action="{{ url('/databarang/datagajibarang') }}">
                    <!-- Week Selector -->
                    <div class="form-group mb-2 ml-5">
                        <label for="minggu">Minggu ke-</label>
                        <select class="form-control ml-3" name="minggu" id="minggu">
                            <option value="">Pilih Minggu</option>
                            @foreach (range(1, 52) as $week)
                                <option value="{{ $week }}" {{ request()->get('minggu') == $week ? 'selected' : '' }}>Minggu {{ $week }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- year selector --}}
                    <div class="form-group mb-2 ml-5">
                        <label for="tahun">Tahun</label>
                        <select class="form-control ml-3" name="tahun" id="tahun">
                            <option value="">Pilih Tahun</option>
                            @foreach (range(2020, now()->year + 4) as $year)
                                <option value="{{ $year }}" {{ request()->get('tahun') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mb-2 ml-auto">
                        <i class="fas fa-eye"></i> Tampilkan Data
                    </button>
                    <a href="" class="btn btn-success mb-2 ml-3">
                        <i class="fas fa-plus"></i> Input Data Gaji Barang
                    </a>
                </form>
            </div>
        </div>

        @php
            // Set the selected year and week, or default to the current  first week
            $minggu = request()->get('minggu') ?: 1;
            // Set the year to the selected year or the current year
            $tahun = request()->get('tahun') ?: now()->year;
        @endphp

        <div class="alert alert-info">
            Menampilkan Data Pendapatan Pegawai Minggu ke-: <span class="font-weight-bold">{{ $minggu }}</span> Tahun: <span class="font-weight-bold">{{ $tahun }}</span>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Mingguan dan Gaji Barang</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
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
                                            <td>10/01/2024</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>
                                                <center>
                                                    <a class="btn btn-sm btn-info" href="{{ url('/databarang/edit') }}">
                                                      <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a onclick="return confirm('Yakin Hapus?')" class="btn btn-sm btn-danger" 
                                                       href="">
                                                      <i class="fas fa-trash"></i>
                                                    </a>
                                                  </center>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td><strong>Total Gaji</strong></td>
                                            <td colspan="1">1</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    
    <script src="script.js"></script>

    <!-- jQuery -->
<script src="{{ asset('admin-lte') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin-lte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('admin-lte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/jszip/jszip.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('admin-lte') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin-lte') }}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('admin-lte') }}/dist/js/demo.js"></script> --}}
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>