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
            
            $upah = DB::table('upah')
                ->where('jabatan', $karyawan->jabatan_karyawan)
                ->value('upah');
        
            if (!$upah) {
                $upah = 0; // Defaultkan ke 0 jika upah tidak ditemukan
            }
        
            $gajiBarang = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan->id)
                ->get();
        
            $gajiPerMinggu = []; // Array untuk menyimpan total gaji per minggu
            $tanggalTerbaruPerMinggu = []; // Array untuk menyimpan tanggal terbaru per minggu
        
            foreach ($gajiBarang as $gb) {
                $tanggal = Carbon::parse($gb->tanggal); // Konversi tanggal pengerjaan ke Carbon
                $minggu = $tanggal->weekOfYear; // Tentukan minggu ke dalam tahun
                $tahun = $tanggal->year; // Tentukan tahun pengerjaan
        
                // Hitung total pengerjaan dan upah untuk minggu ini
                if (!isset($gajiPerMinggu[$tahun][$minggu])) {
                    $gajiPerMinggu[$tahun][$minggu] = 0;
                    $tanggalTerbaruPerMinggu[$tahun][$minggu] = $tanggal; // Inisialisasi tanggal terbaru
                }
        
                $gajiPerMinggu[$tahun][$minggu] += $gb->total_pengerjaan * $upah;
        
                // Perbarui tanggal terbaru jika tanggal saat ini lebih baru
                if ($tanggal->greaterThan($tanggalTerbaruPerMinggu[$tahun][$minggu])) {
                    $tanggalTerbaruPerMinggu[$tahun][$minggu] = $tanggal;
                }
            }
        
            // Tambahkan data gaji barang ke dalam array data laporan
            foreach ($gajiPerMinggu as $tahun => $mingguData) {
                foreach ($mingguData as $minggu => $gajiTotal) {
                    $dataGajiBarang[] = [
                        'nama_karyawan' => $karyawan->nama_karyawan,
                        'jabatan_karyawan' => $karyawan->jabatan_karyawan,
                        'minggu' => $minggu, // Minggu ke-berapa
                        'tahun' => $tahun, // Tahun pengerjaan
                        'gaji' => $gajiTotal, // Total gaji untuk minggu ini
                        'tanggal_penggajian' => $tanggalTerbaruPerMinggu[$tahun][$minggu]->format('Y-m-d'), // Tanggal terbaru untuk minggu ini
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
