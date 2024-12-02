<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminKaryawanController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $data = DB::table('karyawan')->get();
            return view('datakaryawan', ['data' => $data, 'username' => $username]);;
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function add()
    {
        // return view('add');
        if (Auth::check()) {
            $username = Auth::user()->name;
            $jabatanList = DB::table('upah')->select('jabatan')->distinct()->get();
            return view('add', compact('jabatanList', 'username'));
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function create(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'NIK' => 'required|integer|unique:karyawan,NIK',
            'alamat' => 'required|string|max:255',
            'jabatan_karyawan' => 'required|string|max:255',
        ], [
            'NIK.unique' => 'NIK yang Anda masukkan sudah terdaftar. Silakan gunakan NIK lain.',
            'NIK.required' => 'NIK wajib diisi.',
            'NIK.integer' => 'NIK harus berupa angka.',
        ]);
        // Mengambil data dari request
        $nama_karyawan = ucwords(strtolower($request->nama_karyawan));
        $NIK = $request->NIK;
        $alamat = ucwords(strtolower($request->alamat));
        $jabatan_karyawan = $request->jabatan_karyawan;

        // Insert data ke tabel karyawan
        $add = DB::table('karyawan')->insert([
            'nama_karyawan' => $nama_karyawan,
            'NIK' => $NIK,
            'alamat' => $alamat,
            'jabatan_karyawan' => $jabatan_karyawan,
        ]);

        // Cek apakah data berhasil ditambahkan
        if ($add) {
            return redirect()->route('indexKaryawan')
                ->with('success', 'Data karyawan berhasil ditambahkan!');
        } else {
            return redirect()->route('indexKaryawan')
                ->with('failed', 'Data karyawan gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $data = DB::table('karyawan')
                ->where('id', $id)
                ->first();
            $jabatanList = DB::table('upah')->select('jabatan')->get();
            return view('edit', ['data' => $data, 'jabatanList' => $jabatanList, 'username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function update(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'id' => 'required|exists:karyawan,id',
            'nama_karyawan' => 'required|string|max:255',
            'NIK' => 'required|integer|unique:karyawan,NIK,' . $request->id,
            'alamat' => 'required|string|max:255',
            'jabatan_karyawan' => 'required|string|max:255',
        ], [
            'NIK.unique' => 'NIK yang Anda masukkan sudah terdaftar. Silakan gunakan NIK lain.',
            'NIK.required' => 'NIK wajib diisi.',
            'NIK.integer' => 'NIK harus berupa angka.',
        ]);
        
        // Mengambil data dari request
        $id = $request->id;
        $nama_karyawan = ucwords(strtolower($request->nama_karyawan));
        $NIK = $request->NIK;
        $alamat = ucwords(strtolower($request->alamat));
        $jabatan_karyawan = $request->jabatan_karyawan;

        // Melakukan update data karyawan
        $update = DB::table('karyawan')->where('id', $id)->update([
            'nama_karyawan' => $nama_karyawan,
            'NIK' => $NIK,
            'alamat' => $alamat,
            'jabatan_karyawan' => $jabatan_karyawan,
            'update_at' => now(), // Mengupdate timestamp untuk kolom update_at
        ]);

        // Mengecek apakah update berhasil atau tidak
        if ($update) {
            return redirect()->route('indexKaryawan')
                ->with('success', 'Data karyawan berhasil diupdate!');
        } else {
            return redirect()->route('editKaryawan', ['id' => $id])
                ->with('error', 'Gagal mengupdate data karyawan.');
        }
    }

    public function delete($id)
    {
        // Ambil data karyawan berdasarkan id
        $karyawanData = DB::table('karyawan')->where('id', $id)->first();

        // Cek apakah karyawan memiliki jabatan 'tenun'
        if ($karyawanData) {
            if (strtolower($karyawanData->jabatan_karyawan) == 'tenun') {
                // Jika jabatan 'tenun', hapus data dari tabel 'gajitenun'
                DB::table('gajitenun')->where('karyawan_id', $karyawanData->id)->delete();
            } else {
                // Jika jabatan selain 'tenun', hapus data dari tabel 'gajibarang'
                DB::table('gajibarang')->where('karyawan_id', $karyawanData->id)->delete();
            }

            // Hapus data karyawan
            $delete = DB::table('karyawan')->where('id', $id)->delete();

            if ($delete) {
                return redirect()->route('indexKaryawan')->with('success', 'Data karyawan dan gaji terkait berhasil dihapus!');
            } else {
                return redirect()->route('indexKaryawan')->with('error', 'Gagal menghapus data karyawan!');
            }
        } else {
            return redirect()->route('indexKaryawan')->with('error', 'Data karyawan tidak ditemukan!');
        }
    }

    public function datakaryawan($id)
    {
        // Ambil data karyawan berdasarkan ID
        $data = DB::table('karyawan')
            ->where('id', $id)
            ->first(); // Ambil satu karyawan berdasarkan ID

        // Cek apakah data ditemukan
        if (!$data) {
            return redirect()->route('indexKaryawan')
                ->with('error', 'Karyawan tidak ditemukan!');
        }

        // Cek apakah data upah untuk jabatan karyawan ada di tabel 'upah'
        $upah = DB::table('upah')
            ->where('jabatan', $data->jabatan_karyawan)
            ->first(); // Cek apakah ada data upah untuk jabatan tersebut

        // Jika data upah tidak ada, beri peringatan dan jangan arahkan ke halaman gaji
        if (!$upah) {
            return redirect()->route('indexKaryawan')
                ->with('error', 'Data upah untuk jabatan ' . $data->jabatan_karyawan . ' belum diisi. Silakan inputkan data upah terlebih dahulu.');
        }

        // Cek apakah upah untuk jabatan 'thr tenun' sudah ada
        if ($data->jabatan_karyawan == 'Tenun') {
            $thr_tenun = DB::table('upah')
                ->where('jabatan', 'thr tenun')
                ->first(); // Cek apakah ada data upah untuk 'thr tenun'

            if (!$thr_tenun) {
                return redirect()->route('indexKaryawan')
                    ->with('error', 'Upah untuk "thr tenun" belum diisi. Silakan inputkan data upah terlebih dahulu.');
            }
        }

        // Jika jabatan karyawan 'tenun', arahkan ke halaman datatenun
        if ($data->jabatan_karyawan == 'Tenun') {
            return redirect()->route('indexGajiTenun', $id);
        } else {
            return redirect()->route('indexGajiBarang', $id);
        }
    }
}
