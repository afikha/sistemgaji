<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Tambahkan Carbon

class AdminlaporangajiController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $username = Auth::user()->name;

            // Ambil data dari tabel karyawan
            $data = DB::table('karyawan')
                ->select('karyawan.id', 'karyawan.nama_karyawan', 'karyawan.jabatan_karyawan') // Tambahkan 'id'
                ->get();

            return view('laporan.laporangaji', [
                'data' => $data,
                'username' => $username
            ]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function laporandetail(Request $request, $id)
{
    if (Auth::check()) {
        $username = Auth::user()->name;

        // Ambil data karyawan untuk mendapatkan jabatan_karyawan
        $karyawan = DB::table('karyawan')->where('id', $id)->first();
        if (!$karyawan) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Ambil upah berdasarkan jabatan_karyawan
        $upah = DB::table('upah')
            ->where('jabatan', $karyawan->jabatan_karyawan)
            ->value('upah');
        if (!$upah) {
            return redirect()->back()->with('error', 'Upah untuk jabatan karyawan tidak ditemukan.');
        }

        // Ambil data gaji dari tabel gajitenun berdasarkan ID karyawan
        $dataTenun = DB::table('gajitenun')
            ->select('gaji', 'tanggal')
            ->where('karyawan_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                // Ubah tanggal dengan menambahkan 5 hari
                $item->tanggal = Carbon::parse($item->tanggal)->addDays(5)->format('Y-m-d');
                return $item;
            });

        // Ambil data dari tabel gajibarang dan hitung total pengerjaan per minggu
        $dataBarang = DB::table('gajibarang')
            ->select(
                'minggu',
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('SUM(total_pengerjaan) as total_pengerjaan')
            )
            ->where('karyawan_id', $id)
            ->groupBy('minggu', DB::raw('YEAR(tanggal)'))
            ->orderBy(DB::raw('MAX(tanggal)'), 'desc')
            ->get()
            ->map(function ($item) use ($upah) {
                 // Ambil data tanggal paling lampau dalam minggu tersebut
                $latestDate = DB::table('gajibarang')
                    ->where('minggu', $item->minggu)
                    ->whereYear('tanggal', $item->tahun)
                    ->min('tanggal'); // Ambil tanggal paling akhir dari minggu tersebut
                
                // Jika ada tanggal, tambahkan 5 hari
                if ($latestDate) {
                    $item->tanggal = Carbon::parse($latestDate)->addDays(5)->format('Y-m-d');
                }

                // Hitung gaji untuk data barang
                $item->gaji = $item->total_pengerjaan * $upah;
                return $item;
            });

        // Gabungkan data tenun dan barang
        $data = $dataTenun->merge($dataBarang)
            ->sortByDesc('tanggal');
        $totalGaji = $data->sum('gaji'); // Hitung total gaji dari data

        return view('laporan.laporandetail', [
            'data' => $data,
            'username' => $username,
            'nama_karyawan' => $karyawan->nama_karyawan,
            'jabatan_karyawan'=> $karyawan ->jabatan_karyawan,
            'total_gaji' => $totalGaji
        ]);
    } else {
        return redirect()->route('indexLogin')->with('error', 'Silakan Login');
    }
}

}
