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
    <!-- Menampilkan Nama Karyawan -->
    @php
        $karyawan = DB::table('karyawan')->where('id', $karyawan_id)->first();
    @endphp

    <h5>GAJI TENUN <strong style="text-transform: uppercase;">{{ $karyawan->nama_karyawan }}</strong></h5>
    <div class="card mb-3">
      <div class="card mb-3">
        <div class="card-header bg-primary text-white">
          Filter Data Gaji Tenun
        </div>
        <div class="card-body">
          <form class="form-inline" method="GET" action="{{route('indexGajiTenun', $karyawan_id)}}">
            <div class="form-group mb-2 ml-5">
              <label for="tahun">Tahun</label>
              <select class="form-control ml-3" name="tahun_awal" id="tahun_awal">
                <option value="">Tahun Awal</option>
                @foreach (range(2020, now()->year + 4) as $year)
                <option value="{{ $year }}" {{ request()->get('tahun_awal') == $year ? 'selected' : '' }}>
                  {{ $year }} / {{$year+1}}
                </option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="btn btn-primary mb-2 ml-auto">
              <i class="fas fa-eye"></i> Tampilkan Data
            </button>
            <a href="{{route('addViewGajiTenun', 'karyawan_id='.$karyawan_id)}}" class="btn btn-success mb-2 ml-3">
              <i class="fas fa-plus"></i> Input Data Gaji Tenun
            </a>
            <button type="button" class="btn btn-primary mb-2 ml-3" data-toggle="modal" data-target="#modalTHR">
              <i class="fas fa-gift"></i> THR
            </button>

          </form>
        </div>
      </div>

      @php
      // Set the year to the selected year or the current year
      $total_thr = 0;
      $tahun_awal = request()->get('tahun_awal') ?: now()->year;
      @endphp

      <div class="alert alert-info">
        Menampilkan Data Kehadiran Pegawai Tahun: <span class="font-weight-bold">{{ $tahun_awal }}/{{ $tahun_awal + 1 }}</span>
      </div>


      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Mingguan dan Gaji Tenun</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Minggu ke-</th>
                        <th>Tanggal</th>
                        <th>Hari 1</th>
                        <th>Hari 2</th>
                        <th>Hari 3</th>
                        <th>Hari 4</th>
                        <th>Hari 5</th>
                        <th>Hari 6</th>
                        <th>Total Pengerjaan</th>
                        <th>Gaji</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($data as $d)
                      <tr>
                        <td>{{$d->minggu}}</td>
                        <td >{{$d->tanggal}}</td>
                        <td>{{$d->hari_1}}</td>
                        <td>{{$d->hari_2}}</td>
                        <td>{{$d->hari_3}}</td>
                        <td>{{$d->hari_4}}</td>
                        <td>{{$d->hari_5}}</td>
                        <td>{{$d->hari_6}}</td>
                        <td>{{$d->total_pengerjaan}} </td>
                        <td class="text-end">@currency($d->gaji)</td>
                        @php
                        $total_thr = $total_thr + ($d->total_pengerjaan * $thr_tenun);
                        @endphp
                        <td>
                          <center>
                            <a class="btn btn-sm btn-info" href="{{route('editGajiTenun', $d->id)}}"><i class="fas fa-edit"></i></a>
                            <a onclick="return confirm('Yakin Hapus?')" class="btn btn-sm btn-danger" href="{{route('deleteGajiTenun', $d->id)}}"><i class="fas fa-trash"></i></a>
                          </center>
                        </td>
                      </tr>
                      @endforeach
                  </table>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="modalTHR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tunjangan Hari Raya {{ $tahun_awal }}/{{$tahun_awal + 1}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        Total THR anda @currency($total_thr)
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                      </div>
                    </div>
                  </div>
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
    </div>

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