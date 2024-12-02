<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Laporan Pasien</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-klinik.png') }}">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="{{ asset('admin-laporan') }}/dist/css/adminlte.min.css">

    <style>
        @media print {
            body {
                font-size: 12px;
            }

            .btn,
            .form-label,
            #minDate,
            #maxDate,
            #applyFilter,
            #printReport {
                display: none;
                /* Sembunyikan elemen yang tidak diperlukan saat cetak */
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th,
            table td {
                border: 1px solid #000;
                padding: 8px;
            }

            .table-responsive {
                overflow: visible;
                /* Pastikan tabel tetap terlihat */
            }
        }
    </style>
</head>

<body>
    @include("header")
    <div class="container mt-4">
        @include("container")
        <h3 class="text-center mt-4">Data Laporan Karyawan</h3>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="minDate" class="form-label">Tanggal Mulai:</label>
                <input type="date" id="minDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="maxDate" class="form-label">Tanggal Akhir:</label>
                <input type="date" id="maxDate" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button id="applyFilter" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-md-3 align-self-end text-end">
                <button id="printTable" class="btn btn-success">Cetak Laporan</button>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Gaji</th>
                        <th>Tanggal Penggajian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataLaporan as $laporan)
                    <tr>
                        <td>{{ $laporan->nama_karyawan }}</td>
                        <td>{{ $laporan->jabatan_karyawan }}</td>
                        <td>{{ $laporan->gaji }}</td>
                        <td>{{ $laporan->tanggal_penggajian }}</td>
                    </tr>
                @endforeach                
                </tbody>
            </table>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi DataTable
            var table = $('#dataTable').DataTable();

            // Custom filter untuk rentang tanggal
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#minDate').val();
                    var max = $('#maxDate').val();
                    var date = data[3]; // Kolom ke-4 (Tanggal Registrasi)

                    // Jika tidak ada input tanggal, tampilkan semua data
                    if ((min === "" && max === "") ||
                        (min === "" && date <= max) ||
                        (min <= date && max === "") ||
                        (min <= date && date <= max)) {
                        return true;
                    }
                    return false;
                }
            );

            // Event listener untuk tombol filter
            $('#applyFilter').on('click', function () {
                table.draw();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil elemen input tanggal
            var minDateInput = document.getElementById('minDate');
            var maxDateInput = document.getElementById('maxDate');

            // Dapatkan tanggal hari ini dalam format YYYY-MM-DD
            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // Bulan ditambah 1 karena dimulai dari 0
            var dd = String(today.getDate()).padStart(2, '0');

            var maxDate = `${yyyy}-${mm}-${dd}`;

            // Tetapkan atribut max ke input tanggal
            minDateInput.setAttribute('max', maxDate);
            maxDateInput.setAttribute('max', maxDate);
        });

    </script>
    <script>
        document.getElementById('printTable').addEventListener('click', function () {
            var tableContent = document.querySelector('.table-responsive').innerHTML; // Ambil isi tabel
            var newWindow = window.open('', '', 'width=800,height=600'); // Buka jendela baru
            newWindow.document.write('<html><head><title>Cetak Laporan</title>');
            newWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">'); // Tambahkan style jika diperlukan
            newWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; }</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(tableContent); // Tambahkan konten tabel
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.print(); // Cetak halaman
        });
    </script>

</body>

</html>