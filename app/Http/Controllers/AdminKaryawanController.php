<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminKaryawanController extends Controller
{
    public function index() {
        $data = DB::table('karyawan')->get();
        return view('datakaryawan', ['data' => $data]);;
    }

    public function add(){
        // return view('add');
        $jabatanList = DB::table('upah')->select('jabatan')->distinct()->get();
        return view('add', compact('jabatanList'));
    }

    public function create(Request $request){
        // Mengambil data dari request
        $nama_karyawan = $request->nama_karyawan;
        $jabatan_karyawan = $request->jabatan_karyawan;

        // Insert data ke tabel karyawan
        $add = DB::table('karyawan')->insert([
            'nama_karyawan' => $nama_karyawan,
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

    public function edit($id){
            $data = DB::table('karyawan')
                ->where('id', $id)
                ->first();
            $jabatanList = DB::table('upah')->select('jabatan')->get();
            return view('edit', ['data' => $data, 'jabatanList' => $jabatanList]);
    }

    public function update(Request $request)
{
    // Validasi data yang masuk
    $request->validate([
        'id' => 'required|exists:karyawan,id', 
        'nama_karyawan' => 'required|string|max:255', 
        'jabatan_karyawan' => 'required|string|max:255', 
    ]);

    // Mengambil data dari request
    $id = $request->id;
    $nama_karyawan = $request->nama_karyawan;
    $jabatan_karyawan = $request->jabatan_karyawan;

    // Melakukan update data karyawan
    $update = DB::table('karyawan')->where('id', $id)->update([
        'nama_karyawan' => $nama_karyawan,
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
        $karyawaData = DB::table('karyawan')->where('id', $id)->first();
        $delete = DB::table('karyawan')->where('id', $id)->delete();

        return redirect()->route('indexKaryawan')
            ->with('success', 'Data berhasil dihapus!');
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
        if ($data->jabatan_karyawan == 'tenun') {
            return view('datatenun/datagajitenun', compact('data')); // Arahkan ke halaman datatenun
        } else {
            return view('databarang/datagajibarang', compact('data')); // Arahkan ke halaman databarang
        }
    }


}
