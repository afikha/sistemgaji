<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\GajiTenun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()) {
            // Mengambil data dari tabel gaji tenun dengan informasi karyawan
            $dataGajiTenun = GajiTenun::join('karyawan', 'gajitenun.karyawan_id', '=', 'karyawan.id')
                ->select(
                    'karyawan.nama_karyawan', 
                    'karyawan.jabatan_karyawan', 
                    'gajitenun.gaji', 
                    DB::raw("DATE_ADD(gajitenun.tanggal, INTERVAL 5 DAY) as tanggal_penggajian")
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

                $gajiPerMinggu = []; // Array untuk menyimpan total pengerjaan dan tanggal maksimum per minggu

                // Loop untuk mengumpulkan total pengerjaan dan tanggal maksimum per minggu
                foreach ($gajiBarang as $gb) {
                    $minggu = $gb->minggu; // Gunakan minggu yang ada dalam tabel gajibarang
                    $tahun = Carbon::parse($gb->tanggal)->year; // Tentukan tahun berdasarkan tanggal pengerjaan

                    // Inisialisasi data minggu jika belum ada
                    if (!isset($gajiPerMinggu[$tahun][$minggu])) {
                        $gajiPerMinggu[$tahun][$minggu] = [
                            'total_pengerjaan' => 0,
                            'tanggal_terakhir' => $gb->tanggal
                        ];
                    }

                    // Tambahkan total pengerjaan dan perbarui tanggal maksimum jika diperlukan
                    $gajiPerMinggu[$tahun][$minggu]['total_pengerjaan'] += $gb->total_pengerjaan;
                    if (Carbon::parse($gb->tanggal)->gt(Carbon::parse($gajiPerMinggu[$tahun][$minggu]['tanggal_terakhir']))) {
                        $gajiPerMinggu[$tahun][$minggu]['tanggal_terakhir'] = $gb->tanggal;
                    }
                }

                // Tambahkan data gaji barang ke dalam array data laporan
                foreach ($gajiPerMinggu as $tahun => $mingguData) {
                    foreach ($mingguData as $minggu => $data) {
                        // Hitung gaji per minggu (total pengerjaan * upah)
                        $gajiTotal = $data['total_pengerjaan'] * $upah;

                        // Simpan data laporan per minggu
                        $dataGajiBarang[] = [
                            'nama_karyawan' => $karyawan->nama_karyawan,
                            'jabatan_karyawan' => $karyawan->jabatan_karyawan,
                            'minggu' => $minggu, // Minggu ke-berapa
                            'tahun' => $tahun, // Tahun pengerjaan
                            'gaji' => $gajiTotal, // Total gaji untuk minggu ini
                            'tanggal_penggajian' => $data['tanggal_terakhir'], // Tanggal terakhir dalam minggu tersebut
                        ];
                    }
                }
            }

            // Gabungkan data gaji tenun dan gaji barang
            $dataLaporan = collect($dataGajiTenun)->merge($dataGajiBarang);

            // Mendapatkan username pengguna yang sedang login
            $username = Auth::user()->name;

            // Mengirim data ke view
            return view('laporan', compact('dataLaporan', 'username'));
        } else {
            return redirect()->route('indexLogin', ['type' => 'admin']);
        }
    }
}