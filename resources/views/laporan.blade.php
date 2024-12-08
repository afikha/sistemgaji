<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Sistem Penggajian Pabrik Sarung Goyor</title>

    <!-- Custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('admin-lte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('admin-lte') }}/dist/css/adminlte.min.css">

    <style>
        h5 {
            font-weight: normal; /* Mengurangi ketebalan teks */
        }
    
        @media print {
            body {
                font-size: 12px;
            }
    
            .btn,
            .form-label,
            #minDate,
            #maxDate,
            #applyFilter,
            #printTable,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none !important;
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
                overflow: visible !important;
            }
        }
    </style>
    
</head>

<body>
    <!-- Navbar -->
    @include("header")
    <!-- /.navbar -->
    <!-- container -->
    @include("container")
    <!-- /.container  -->

    <div class="container mt-4">
        <!-- Menampilkan Pesan Error jika ada -->
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <h5>DATA LAPORAN KARYAWAN</h5>
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
                        <td class="text-start">{{ $laporan['nama_karyawan'] }}</td>
                        <td class="text-start">{{ $laporan['jabatan_karyawan'] }}</td>
                        <td class="text-end">@currency($laporan['gaji'])</td>
                        <td>{{ $laporan['tanggal_penggajian'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('admin-lte') }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('admin-lte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin-lte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('admin-lte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('admin-lte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('admin-lte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script>
        document.getElementById('printTable').addEventListener('click', function () {
            // Ambil elemen tabel tanpa DataTable tambahan
            var tableElement = document.querySelector('#dataTable');
            
            // Simpan elemen DataTables tambahan yang akan disembunyikan
            var dataTableExtras = document.querySelectorAll('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate');
            dataTableExtras.forEach(el => el.style.display = 'none'); // Sembunyikan elemen tambahan
    
            // Buat jendela baru untuk cetak
            var newWindow = window.open('', '', 'width=800,height=600');
            newWindow.document.write('<html><head><title>Cetak Laporan</title>');
            newWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">');
            newWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; }</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write('<h4>Data Laporan Karyawan</h4>');
            newWindow.document.write(tableElement.outerHTML); // Cetak hanya tabel
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.print();
    
            // Kembalikan elemen tambahan DataTables ke tampilan awal
            dataTableExtras.forEach(el => el.style.display = '');
        });
    </script>    
    
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

            var maxDate = ${yyyy}-${mm}-${dd};

            // Tetapkan atribut max ke input tanggal
            minDateInput.setAttribute('max', maxDate);
            maxDateInput.setAttribute('max', maxDate);
        });

    </script>

</body>

</html>
