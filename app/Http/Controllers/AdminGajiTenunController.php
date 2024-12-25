<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AdminGajiTenunController extends Controller
{

    public function index($karyawan_id)
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $tahun_awal = request('tahun_awal');
            $thr_tenun = DB::table('upah')
                ->where('jabatan', 'thr tenun')
                ->first()->upah;

            if ($tahun_awal == null) {
                 // Jika tahun awal tidak diberikan, ambil semua data karyawan
                $data = DB::table('gajitenun')
                    ->where('karyawan_id', $karyawan_id)
                    ->get();
            } else {
                 // Cek data awal dari tahun sebelumnya
                $cekdataawal = DB::table('gajitenun')
                    ->whereYear('tanggal', $tahun_awal - 1)
                    ->where('karyawan_id', $karyawan_id)
                    ->where('minggu', 1)
                    ->first();
                // Jika ada data di tahun sebelumnya, cek data minggu pertama di tahun $tahun_awal
                if ($cekdataawal != null) {
                    $cekdataawal = DB::table('gajitenun')
                        ->whereYear('tanggal', $tahun_awal)
                        ->where('karyawan_id', $karyawan_id)
                        ->where('minggu', 1)
                        ->first();
                } else {
                    $cekdataawal = DB::table('gajitenun')
                        ->whereYear('tanggal', $tahun_awal)
                        ->where('karyawan_id', $karyawan_id)
                        ->where('minggu', 1)
                        ->first();
                }
                // Jika ditemukan data awal ($cekdataawal), maka dibuat rentang waktu 343 hari dari tanggal awal untuk mengambil data.
                if ($cekdataawal != null) {
                    $tanggalakhir = Carbon::createFromFormat('Y-m-d', $cekdataawal->tanggal)->addDays(343);
                    $data = DB::table('gajitenun')
                        ->where('karyawan_id', $karyawan_id)
                        ->whereBetween('tanggal', [$cekdataawal->tanggal, $tanggalakhir->format('Y-m-d')])
                        ->get();
                } else {
                    // Jika tidak ada data awal, variabel $data akan diisi array kosong.
                    $data = [];
                }
            }
            return view('datatenun/datagajitenun', ['data' => $data, 'karyawan_id' => $karyawan_id, 'username' => $username, 'thr_tenun' => $thr_tenun]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }


    public function add()
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $karyawan_id = request('karyawan_id');

            // Cek data terakhir berdasarkan karyawan_id
            $lastData = DB::table('gajitenun')
                ->where('karyawan_id', $karyawan_id)
                ->orderBy('minggu', 'desc')
                ->first();

            if ($lastData) {
                // Jika data sebelumnya ada, ambil minggu dan tanggal terakhir
                $nextMinggu = $lastData->minggu + 1;
                $nextTanggal = Carbon::parse($lastData->tanggal)->addDays(7)->format('Y-m-d');
            } else {
                // Jika tidak ada data sebelumnya, minggu dimulai dari 1, tanggal kosong
                $nextMinggu = 1;
                $nextTanggal = null;
            }

            return view('datatenun.add', [
                'karyawan_id' => $karyawan_id,
                'username' => $username,
                'nextMinggu' => $nextMinggu,
                'nextTanggal' => $nextTanggal,
            ]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function create(Request $request)
    {
        // Mengambil data dari request
        $minggu = $request->minggu;
        $tanggal = $request->tanggal;
        $hari_1 = $request->hari_1;
        $hari_2 = $request->hari_2;
        $hari_3 = $request->hari_3;
        $hari_4 = $request->hari_4;
        $hari_5 = $request->hari_5;
        $hari_6 = $request->hari_6;
        $total_pengerjaan = $request->total_pengerjaan;
        $upahsaatini = $request->upahsaatini;
        $karyawan_id = $request->karyawan_id;
        $gaji = $request->gaji;

        // Validasi tanggal dan minggu
        if (is_null($tanggal) || is_null($minggu)) {
            return redirect()->route('addViewGajiTenun', ['karyawan_id' => $request->karyawan_id])
                ->with('failed', 'Tanggal dan minggu harus diisi!');
        }

        // Dapatkan tahun dari tanggal yang dimasukkan
        $tahun = Carbon::parse($tanggal)->year;

        // Cek apakah sudah ada minggu ke-1 di tahun yang sama
        $cekMinggu1 = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', 1)
            ->exists();

        // Jika minggu ke-1 sudah ada di tahun yang sama, maka tahun berikutnya harus dimulai dari minggu ke-1
        if ($minggu == 1 && $cekMinggu1) {
            return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Minggu ke-1 sudah ada pada tahun ini, harap mulai lagi dari minggu ke-1 di tahun depan!');
        }

        // Jika minggu lebih dari 1, cek apakah minggu sebelumnya sudah ada di tahun yang sama
        if ($minggu > 1) {
            $cekMingguSebelumnya = DB::table('gajitenun')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1)
                ->exists();

            if (!$cekMingguSebelumnya) {
                return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                    ->with('failed', 'Periksa Minggu yang Anda inputkan. Minggu sebelumnya harus ada terlebih dahulu!');
            }

            // Cari tanggal minggu sebelumnya
            $tanggalMingguSebelumnya = DB::table('gajitenun')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1)
                ->value('tanggal');

            if ($tanggalMingguSebelumnya) {
                $tanggalMingguSebelumnya = Carbon::parse($tanggalMingguSebelumnya);
                $tanggalValidMingguIni = $tanggalMingguSebelumnya->addDays(7); // Tambahkan 7 hari ke tanggal minggu sebelumnya

                // Validasi apakah tanggal input tepat sesuai aturan (7 hari setelah minggu sebelumnya)
                if (Carbon::parse($tanggal)->ne($tanggalValidMingguIni)) {
                    return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                        ->with('failed', "Tanggal untuk minggu ke-{$minggu} harus tepat 7 hari setelah tanggal minggu ke-" . ($minggu - 1) . " ({$tanggalValidMingguIni->format('Y-m-d')}).");
                }
            }
        }

        // Cek apakah minggu sudah ada dalam periode yang sama (sama karyawan_id dan tahun)
        $cekMinggu = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu)
            ->first();

        if ($cekMinggu) {
            return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Minggu ini sudah ada untuk periode tahun tersebut!');
        }

        // Cek tanggal tidak boleh lebih dari hari ini
        if ($tanggal > Carbon::now()->format('Y-m-d')) {
            return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal tidak boleh lebih dari hari ini!');
        }

        // Cek jika tanggal sudah ada dalam database untuk karyawan yang sama
        $cektanggal = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($cektanggal) {
            return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal yang Anda masukkan sudah ada!');
        }

        // Menghitung total pengerjaan sebagai jumlah hari 1 hingga hari 6
        $total_pengerjaan = $hari_1 + $hari_2 + $hari_3 + $hari_4 + $hari_5 + $hari_6;

        // Mengambil upah berdasarkan jabatan tenun dari tabel upah
        $upah = DB::table('upah')->where('jabatan', 'tenun')->value('upah');

        if ($upah === null) {
            return redirect()->route('indexGajiTenun')
                ->with('failed', 'Data upah untuk jabatan tidak ditemukan.');
        }

        // Menghitung gaji dengan mengalikan total pengerjaan dengan upah
        $gaji = $total_pengerjaan * $upah;
        $upahsaatini = $upah;

        // Insert data ke tabel gajitenun
        $add = DB::table('gajitenun')->insert([
            'minggu' => $minggu,
            'tanggal' => $tanggal,
            'hari_1' => $hari_1,
            'hari_2' => $hari_2,
            'hari_3' => $hari_3,
            'hari_4' => $hari_4,
            'hari_5' => $hari_5,
            'hari_6' => $hari_6,
            'total_pengerjaan' => $total_pengerjaan,
            'upahsaatini' => $upahsaatini,
            'gaji' => $gaji,
            'karyawan_id' => $karyawan_id
        ]);

        // Cek apakah data berhasil ditambahkan
        if ($add) {
            return redirect()->route('indexGajiTenun', $karyawan_id)
                ->with('success', 'Data gaji tenun berhasil ditambahkan!');
        } else {
            return redirect()->route('indexGajiTenun', $karyawan_id)
                ->with('failed', 'Data gaji tenun gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $data = DB::table('gajitenun')
                ->where('id', $id)
                ->first();
            return view('datatenun.edit', ['data' => $data, 'username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function update(Request $request)
    {
        // Mengambil data dari request
        $id = $request->id;
        $minggu = $request->minggu;
        $tanggal = $request->tanggal;
        $hari_1 = $request->hari_1;
        $hari_2 = $request->hari_2;
        $hari_3 = $request->hari_3;
        $hari_4 = $request->hari_4;
        $hari_5 = $request->hari_5;
        $hari_6 = $request->hari_6;
        $total_pengerjaan = $request->total_pengerjaan;
        $upahsaatini = $request->upahsaatini;
        $karyawan_id = $request->karyawan_id;
        $gaji = $request->gaji;
    
        // Validasi tanggal dan minggu
        if (is_null($tanggal) || is_null($minggu)) {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Tanggal dan minggu harus diisi!');
        }
    
        // Dapatkan tahun dari tanggal yang dimasukkan
        $tahun = Carbon::parse($tanggal)->year;
    
        // Cek apakah sudah ada minggu ke-1 di tahun yang sama
        $cekMinggu1 = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', 1)
            ->exists();
    
            if ($minggu == 1 && $cekMinggu1) {
                $existingMinggu1 = DB::table('gajitenun')
                    ->where('karyawan_id', $karyawan_id)
                    ->whereYear('tanggal', $tahun)
                    ->where('minggu', 1)
                    ->where('id', '!=', $id)
                    ->exists();
            
                if ($existingMinggu1) {
                    return redirect()->route('editGajiTenun', ['id' => $id])
                        ->with('failed', 'Minggu ke-1 sudah ada pada tahun ini untuk data lain, harap mulai lagi dari minggu ke-1 di tahun depan!');
                }
            }
            
    
        // Jika minggu lebih dari 1, cek apakah minggu sebelumnya sudah ada di tahun yang sama
        if ($minggu > 1) {
            $cekMingguSebelumnya = DB::table('gajitenun')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1)
                ->exists();
    
            if (!$cekMingguSebelumnya) {
                return redirect()->route('editGajiTenun', ['id' => $id])
                    ->with('failed', 'Periksa Minggu yang Anda inputkan. Minggu sebelumnya harus ada terlebih dahulu!');
            }
    
            // Cari tanggal minggu sebelumnya
            $tanggalMingguSebelumnya = DB::table('gajitenun')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1)
                ->value('tanggal');
    
            if ($tanggalMingguSebelumnya) {
                $tanggalMingguSebelumnya = Carbon::parse($tanggalMingguSebelumnya);
                $tanggalValidMingguIni = $tanggalMingguSebelumnya->addDays(7); // Tambahkan 7 hari ke tanggal minggu sebelumnya
    
                // Validasi apakah tanggal input tepat sesuai aturan (7 hari setelah minggu sebelumnya)
                if (Carbon::parse($tanggal)->ne($tanggalValidMingguIni)) {
                    return redirect()->route('editGajiTenun', ['id' => $id])
                        ->with('failed', "Tanggal untuk minggu ke-{$minggu} harus tepat 7 hari setelah tanggal minggu ke-" . ($minggu - 1) . " ({$tanggalValidMingguIni->format('Y-m-d')}).");
                }
            }
        }
    
        // Cek apakah minggu sudah ada dalam periode yang sama (sama karyawan_id dan tahun)
        $cekMinggu = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu)
            ->where('id', '!=', $id) // Jangan cek data sendiri
            ->first();
    
        if ($cekMinggu) {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Minggu ini sudah ada untuk periode tahun tersebut!');
        }
    
        // Cek tanggal tidak boleh lebih dari hari ini
        if ($tanggal > Carbon::now()->format('Y-m-d')) {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Tanggal tidak boleh lebih dari hari ini!');
        }
    
        // Cek jika tanggal sudah ada dalam database untuk karyawan yang sama
        $cektanggal = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->where('tanggal', $tanggal)
            ->where('id', '!=', $id) // Jangan cek data sendiri
            ->first();
    
        if ($cektanggal) {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Tanggal yang Anda masukkan sudah ada!');
        }
    
        // Menghitung total pengerjaan sebagai jumlah hari 1 hingga hari 6
        $total_pengerjaan = $hari_1 + $hari_2 + $hari_3 + $hari_4 + $hari_5 + $hari_6;
    
        // Mengambil upah berdasarkan jabatan tenun dari tabel upah
        $upah = DB::table('upah')->where('jabatan', 'tenun')->value('upah');
    
        if ($upah === null) {
            return redirect()->route('indexGajiTenun')
                ->with('failed', 'Data upah untuk jabatan tidak ditemukan.');
        }
    
        // Menghitung gaji dengan mengalikan total pengerjaan dengan upah
        $gaji = $total_pengerjaan * $upah;
        $upahsaatini = $upah;
    
        // Update data di tabel gajitenun
        $update = DB::table('gajitenun')->where('id', $id)->update([
            'minggu' => $minggu,
            'tanggal' => $tanggal,
            'hari_1' => $hari_1,
            'hari_2' => $hari_2,
            'hari_3' => $hari_3,
            'hari_4' => $hari_4,
            'hari_5' => $hari_5,
            'hari_6' => $hari_6,
            'total_pengerjaan' => $total_pengerjaan,
            'upahsaatini' => $upahsaatini,
            'gaji' => $gaji,
            'update_at' => now(),
        ]);
    
        // Cek apakah update berhasil
        if ($update) {
            return redirect()->route('indexGajiTenun', $karyawan_id)
                ->with('success', 'Data gaji tenun berhasil diupdate!');
        } else {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Data gaji tenun gagal diupdate!');
        }
    }

    public function delete($id)
    {
        $gajitenunData = DB::table('gajitenun')->where('id', $id)->first();
        $delete = DB::table('gajitenun')->where('id', $id)->delete();

        return redirect()->route('indexGajiTenun', $gajitenunData->karyawan_id)
            ->with('success', 'Data berhasil dihapus!');
    }
}
