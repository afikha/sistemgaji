<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\GajiTenun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminLaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data dari tabel gaji tenun dengan informasi karyawan
        $dataGajiTenun = GajiTenun::join('karyawan', 'gajitenun.karyawan_id', '=', 'karyawan.id')
            ->select(
                'karyawan.nama_karyawan', 
                'karyawan.jabatan_karyawan', 
                'gajitenun.gaji', 
                'gajitenun.tanggal as tanggal_penggajian'
            )
            ->orderBy('gajitenun.tanggal', 'desc')
            ->get();

        // Data Gaji Barang (berdasarkan tabel upah dan gaji barang)
        $dataGajiBarang = [];
        $karyawanList = Karyawan::all();

        foreach ($karyawanList as $karyawan) {
            // Mengecek apakah karyawan terkait dengan gaji tenun (atau pengecualian lainnya)
            // Misalnya, pengecualian jika jabatan karyawan adalah "Tenun" atau lainnya
            if ($karyawan->jabatan_karyawan == 'Tenun') {
                continue; // Lewati perhitungan gaji barang untuk jabatan 'Tenun'
            }
            
            // Ambil upah berdasarkan jabatan karyawan
            $upah = DB::table('upah')
                ->where('jabatan', $karyawan->jabatan_karyawan)
                ->value('upah');

            if (!$upah) {
                // Jika upah tidak ditemukan, defaultkan ke 0 atau nilai tertentu
                $upah = 0;
            }

            // Ambil data gaji barang yang sudah diinputkan
            $gajiBarang = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan->id)
                ->get();

            $gajiTotal = 0;

            // Hitung total gaji barang berdasarkan data yang ada
            foreach ($gajiBarang as $gb) {
                $gajiTotal += $gb->total_pengerjaan * $upah;
            }

            $tanggal = now()->subDays(rand(0, 30)); // Random tanggal untuk contoh
            $minggu = $tanggal->weekOfYear; // Menentukan minggu ke

            // Menambahkan data gaji barang untuk karyawan ini
            $dataGajiBarang[] = [
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jabatan_karyawan' => $karyawan->jabatan_karyawan,
                'minggu' => $minggu, // Menentukan minggu ke
                'gaji' => $gajiTotal, // Total gaji barang berdasarkan pengerjaan
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
