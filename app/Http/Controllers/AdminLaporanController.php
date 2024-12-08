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
            if ($karyawan->jabatan_karyawan == 'Tenun') {
                continue; // Lewati perhitungan gaji barang untuk jabatan 'Tenun'
            }

            // Ambil upah dari tabel 'upah' berdasarkan jabatan karyawan
            $upah = DB::table('upah')
                ->where('jabatan', $karyawan->jabatan_karyawan)
                ->value('upah');
        
            if (!$upah) {
                $upah = 0; // Defaultkan ke 0 jika upah tidak ditemukan
            }
        
            // Ambil data gaji barang berdasarkan karyawan
            $gajiBarang = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan->id)
                ->get();
        
            $gajiPerMinggu = []; // Array untuk menyimpan total gaji per minggu
        
            // Loop untuk mengumpulkan total pengerjaan per minggu
            foreach ($gajiBarang as $gb) {
                $minggu = $gb->minggu; // Gunakan minggu yang ada dalam tabel gajibarang
                $tahun = Carbon::parse($gb->tanggal)->year; // Tentukan tahun berdasarkan tanggal pengerjaan
        
                // Hitung total pengerjaan untuk minggu ini
                if (!isset($gajiPerMinggu[$tahun][$minggu])) {
                    $gajiPerMinggu[$tahun][$minggu] = 0; // Inisialisasi total pengerjaan per minggu
                }
        
                // Tambahkan total pengerjaan
                $gajiPerMinggu[$tahun][$minggu] += $gb->total_pengerjaan;
            }
        
            // Tambahkan data gaji barang ke dalam array data laporan
            foreach ($gajiPerMinggu as $tahun => $mingguData) {
                foreach ($mingguData as $minggu => $totalPengerjaan) {
                    // Hitung gaji per minggu (total pengerjaan * upah)
                    $gajiTotal = $totalPengerjaan * $upah;
        
                    // Simpan data laporan per minggu
                    $dataGajiBarang[] = [
                        'nama_karyawan' => $karyawan->nama_karyawan,
                        'jabatan_karyawan' => $karyawan->jabatan_karyawan,
                        'minggu' => $minggu, // Minggu ke-berapa
                        'tahun' => $tahun, // Tahun pengerjaan
                        'gaji' => $gajiTotal, // Total gaji untuk minggu ini
                        'tanggal_penggajian' => Carbon::now()->format('Y-m-d'), // Tanggal penggajian
                    ];
                }
            }
        }

        // Gabungkan data gaji tenun dan gaji barang
        $dataLaporan = collect($dataGajiTenun)->merge($dataGajiBarang);

        // Mendapatkan username pengguna yang sedang login
        $username = auth()->check() ? auth()->user()->name : 'Guest';

        // Mengirim data ke view
        return view('laporan', compact('dataLaporan', 'username'));
    }
}
