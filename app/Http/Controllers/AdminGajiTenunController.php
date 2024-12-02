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
            return view('datatenun.add', ['karyawan_id' => $karyawan_id, 'username' => $username]);
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
        $karyawan_id = $request->karyawan_id;
        $gaji = $request->gaji;
        
        // Validasi tanggal dan minggu
        if (is_null($tanggal) || is_null($minggu)) {
            return redirect()->route('addViewGajiTenun', ['karyawan_id' => $request->karyawan_id])
                ->with('failed', 'Tanggal dan minggu harus diisi!');
        }

        // Cek apakah minggu sudah ada dalam periode yang sama (sama karyawan_id dan tahun)
        $tahun_awal = Carbon::parse($tanggal)->year;
        $cekMinggu = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun_awal)
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

        // Menambahkan logika untuk memeriksa tanggal yang dimasukkan untuk minggu selanjutnya
        if ($minggu > 1) {
            // Cari tanggal minggu sebelumnya
            $tanggalMingguSebelumnya = DB::table('gajitenun')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun_awal)
                ->where('minggu', $minggu - 1)
                ->first();

            if ($tanggalMingguSebelumnya) {
                $tanggalMingguSebelumnya = Carbon::parse($tanggalMingguSebelumnya->tanggal);
            
                // Cek apakah tanggal yang dimasukkan lebih cepat dari 6 hari setelah tanggal minggu sebelumnya
                $tanggalMinValid = $tanggalMingguSebelumnya->addDays(6); // Menambahkan 6 hari ke tanggal minggu sebelumnya
                if (Carbon::parse($tanggal)->lt($tanggalMinValid)) {
                    return redirect()->route('addViewGajiTenun', ['karyawan_id' => $karyawan_id])
                        ->with('failed', 'Tanggal harus lebih dari 6 hari setelah tanggal minggu sebelumnya!');
                }
            }
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
        // Validasi data yang masuk
        $request->validate([
            'id' => 'required|exists:gajitenun,id', // Memastikan ID ada di database
            'minggu' => 'required|string',
            'hari_1' => 'required|integer',
            'hari_2' => 'required|integer',
            'hari_3' => 'required|integer',
            'hari_4' => 'required|integer',
            'hari_5' => 'required|integer',
            'hari_6' => 'required|integer',
        ]);

        // Mengambil data dari request
        $id = $request->id;
        $tanggal = $request->tanggal;
        $minggu = $request->minggu;
        $hari_1 = $request->hari_1;
        $hari_2 = $request->hari_2;
        $hari_3 = $request->hari_3;
        $hari_4 = $request->hari_4;
        $hari_5 = $request->hari_5;
        $hari_6 = $request->hari_6;
        $karyawan_id = $request->karyawan_id;

        // Validasi jika tanggal atau minggu kosong
        if (is_null($tanggal) || is_null($minggu)) {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Tanggal dan minggu harus diisi!');
        }

        // Cek apakah minggu sudah ada dalam periode yang sama (sama karyawan_id dan tahun)
        $tahun = Carbon::parse($tanggal)->year;
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

    // Menambahkan logika untuk memeriksa tanggal yang dimasukkan untuk minggu selanjutnya
    if ($minggu > 1) {
        // Cari tanggal minggu sebelumnya
        $tanggalMingguSebelumnya = DB::table('gajitenun')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu - 1)
            ->first();

        if ($tanggalMingguSebelumnya) {
            $tanggalMingguSebelumnya = Carbon::parse($tanggalMingguSebelumnya->tanggal);
        
            // Cek apakah tanggal yang dimasukkan lebih cepat dari 6 hari setelah tanggal minggu sebelumnya
            $tanggalMinValid = $tanggalMingguSebelumnya->addDays(6); // Menambahkan 6 hari ke tanggal minggu sebelumnya
            if (Carbon::parse($tanggal)->lt($tanggalMinValid)) {
                return redirect()->route('editGajiTenun', ['id' => $id])
                    ->with('failed', 'Tanggal harus lebih dari 6 hari setelah tanggal minggu sebelumnya!');
            }
        }
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

        // Melakukan update data di tabel gajitenun
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
            'gaji' => $gaji,
            'update_at' => now(),
        ]);

        // Mengecek apakah update berhasil atau tidak
        if ($update) {
            return redirect()->route('indexGajiTenun', $karyawan_id)
                ->with('success', 'Data gaji tenun berhasil diupdate!');
        } else {
            return redirect()->route('editGajiTenun', ['id' => $id])
                ->with('failed', 'Gagal mengupdate data gaji tenun.');
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
