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

    <!-- container -->
    @include("container")
    <!-- /.container  -->

    <div class="container">
        <h5>DATA KARYAWAN</h5>
        <div class="mb-3">
            <a href="{{route('addViewKaryawan')}}" class="btn" style="background-color: #007bff; color: white;">+ Tambah Data</a><br /><br />
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
                                                <th>No</th>
                                                <th>Nama Karyawan</th>
                                                <th>Alamat</th>
                                                <th>Jobdesk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $d)
                                            <tr>
                                                <td>{{$loop->iteration}}.</td>
                                                <td class="text-start">{{$d->nama_karyawan}}</td>
                                                <td class="text-start">{{$d->alamat}}</td>
                                                <td class="text-start">{{$d->jabatan_karyawan}}</td>
                                                <td>
                                                    <center>
                                                        <a class="btn btn-sm btn-info" href="{{route('editKaryawan', $d->id)}}"> <i class="fas fa-edit"></i> </a>
                                                        <a href="{{route('deleteKaryawan', $d->id)}}" onclick="return confirm('Yakin Hapus?')" class="btn btn-sm btn-danger" href=""> <i class="fas fa-trash"></i></a>
                                                        <a href="{{ route('datakaryawan', $d->id) }}" data-jabatan="{{ $d->jabatan_karyawan }}" class="info-link" href=""> <i class="fas fa-info-circle"></i></a>
                                                    </center>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
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

            <nav>
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
                    $(function() {
                        $("#example1").DataTable({
                            "responsive": true,
                            "lengthChange": false,
                            "autoWidth": false,
                            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    });
                </script>
</body>

</html>