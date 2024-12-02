<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\GajiTenun;

class AdminLaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data dari tabel karyawan dan gajitenun dengan join
        $dataLaporan = GajiTenun::join('karyawan', 'gajitenun.karyawan_id', '=', 'karyawan.id')
            ->select(
                'karyawan.nama_karyawan', 
                'karyawan.jabatan_karyawan', 
                'gajitenun.gaji', 
                'gajitenun.tanggal as tanggal_penggajian'
            )
            ->orderBy('gajitenun.tanggal', 'desc')
            ->get();

        // Mendapatkan username pengguna yang sedang login
        $username = auth()->check() ? auth()->user()->name : 'Guest';

        // Mengirim data ke view
        return view('laporan', compact('dataLaporan', 'username'));
    }
}
