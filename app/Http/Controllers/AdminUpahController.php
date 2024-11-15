<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUpahController extends Controller
{
    public function index()
    {
        $data = DB::table('upah')->get();
        return view('upah.upah', ['data' => $data]);
    }

    public function add()
    {
        return view('upah.add');
    }

    public function create(Request $request){
        // Mengambil data dari request
        $jabatan = $request->jabatan;
        $upah = $request->upah;

        // Insert data ke tabel upah
        $add = DB::table('upah')->insert([
            'jabatan' => $jabatan,
            'upah' => $upah,
        ]);

        // Cek apakah data berhasil ditambahkan
        if ($add) {
            return redirect()->route('indexUpah')
                ->with('success', 'Data karyawan berhasil ditambahkan!');
        } else {
            return redirect()->route('indexUpah')
                ->with('failed', 'Data karyawan gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        $data = DB::table('upah')
            ->where('id', $id)
            ->first();
        return view('upah.edit', ['data' => $data]);
    }

    public function update(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'id' => 'required|exists:upah,id',
            'jabatan' => 'required|string|max:255',
            'upah' => 'required|integer|min:0',
        ]);

        // Update data di tabel upah
        $update = DB::table('upah')->where('id', $request->id)->update([
            'jabatan' => $request->jabatan,
            'upah' => $request->upah,
            'update_at' => now(),
        ]);

        // Redirect dengan pesan sukses atau gagal
        return $update
            ? redirect()->route('indexUpah')->with('success', 'Data upah berhasil diupdate!')
            : redirect()->route('editUpah', ['id' => $request->id])->with('error', 'Gagal mengupdate data upah.');
    }

    public function delete($id)
    {
        $delete = DB::table('upah')->where('id', $id)->delete();

        return $delete
            ? redirect()->route('indexUpah')->with('success', 'Data upah berhasil dihapus!')
            : redirect()->route('indexUpah')->with('error', 'Gagal menghapus data upah.');
    }


}
