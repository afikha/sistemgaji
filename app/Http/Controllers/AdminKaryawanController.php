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
        // Mengambil data dari request
        $nama_karyawan = $request->nama_karyawan;
        $alamat = $request->alamat;
        $jabatan_karyawan = $request->jabatan_karyawan;

        // Insert data ke tabel karyawan
        $add = DB::table('karyawan')->insert([
            'nama_karyawan' => $nama_karyawan,
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
            'alamat' => 'required|string',
            'jabatan_karyawan' => 'required|string|max:255',
        ]);

        // Mengambil data dari request
        $id = $request->id;
        $nama_karyawan = $request->nama_karyawan;
        $alamat = $request->alamat;
        $jabatan_karyawan = $request->jabatan_karyawan;

        // Melakukan update data karyawan
        $update = DB::table('karyawan')->where('id', $id)->update([
            'nama_karyawan' => $nama_karyawan,
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

        // Jika jabatan karyawan 'tenun', arahkan ke halaman datatenun
        if ($data->jabatan_karyawan == 'Tenun') {
            return redirect()->route('indexGajiTenun', $id);
        } else {
            return redirect()->route('indexGajiBarang', $id);
        }
    }
}
