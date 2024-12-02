<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\GajiTenun;
use Illuminate\Support\Facades\DB;

class AdminLaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data dari tabel karyawan dan gaji tenun
        $dataGajiTenun = GajiTenun::join('karyawan', 'gajitenun.karyawan_id', '=', 'karyawan.id')
            ->select(
                'karyawan.nama_karyawan', 
                'karyawan.jabatan_karyawan', 
                'gajitenun.gaji', 
                'gajitenun.tanggal as tanggal_penggajian'
            )
            ->orderBy('gajitenun.tanggal', 'desc')
            ->get();

        // Data Gaji Barang (berdasarkan tabel upah)
        $dataGajiBarang = [];
        $karyawanList = Karyawan::all();

        foreach ($karyawanList as $karyawan) {
            // Ambil upah berdasarkan jabatan
            $upah = DB::table('upah')
                ->where('jabatan', $karyawan->jabatan_karyawan)
                ->value('upah');

            if (!$upah) {
                // Jika upah tidak ditemukan, defaultkan ke 0 atau nilai tertentu
                $upah = 0;
            }

            $tanggal = now()->subDays(rand(0, 30)); // Random tanggal untuk contoh

            $dataGajiBarang[] = [
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jabatan_karyawan' => $karyawan->jabatan_karyawan,
                'minggu' => $tanggal->weekOfYear, // Menentukan minggu ke
                'gaji' => $upah,
                'tanggal_penggajian' => $tanggal->format('Y-m-d'),
            ];
        }

        // Gabungkan data gaji tenun dan gaji barang
        $dataLaporan = collect($dataGajiTenun)->merge($dataGajiBarang);

        // Mendapatkan username pengguna yang sedang login
        $username = auth()->check() ? auth()->user()->name : 'Guest';

        // Mengirim data ke view
        return view('laporan', compact('dataLaporan', 'username'));
    }
}