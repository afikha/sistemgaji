<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminUpahController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $data = DB::table('upah')->get();
            return view('upah.upah', ['data' => $data, 'username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function add()
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            return view('upah.add', ['username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function create(Request $request)
    {
        // Mengambil data dari request
        $jabatan = $request->jabatan;
        $upah = $request->upah;
        $is_upah = $request->is_upah ? 1 : 0;

        // Jika checkbox 'Simpan Hanya Satu Kali' dicentang (is_upah = 1)
        if ($is_upah == 1) {
            // Cek apakah sudah ada jabatan yang sama di database dengan is_upah = 1
            $exists = DB::table('upah')
                ->whereRaw('LOWER(jabatan) = ?', [strtolower($jabatan)])
                ->where('is_upah', 1)
                ->exists();

            if ($exists) {
                // Jika sudah ada, return dengan pesan error
                return redirect()->route('addViewUpah')
                    ->with('failed', 'Jabatan yang sama sudah ada di database');
            }
        }

        // Insert data ke tabel upah
        $add = DB::table('upah')->insert([
            'jabatan' => $jabatan,
            'upah' => $upah,
            'is_upah' => $is_upah,
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
        if (Auth::check()) {
            $username = Auth::user()->name;
            $data = DB::table('upah')
                ->where('id', $id)
                ->first();
            return view('upah.edit', ['data' => $data, 'username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function update(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'id' => 'required|exists:upah,id',
            'jabatan' => 'required|string|max:255',
            'upah' => 'required|integer|min:0',
            'is_upah' => 'nullable|boolean',
        ]);

        $jabatan = $request->jabatan;

        if ($request->is_upah == 1) {
            // Cek apakah ada jabatan yang sama yang sudah ada di database (case-insensitive)
            $exists = DB::table('upah')
                ->whereRaw('LOWER(jabatan) = ?', [strtolower($jabatan)]) // Case-insensitive
                ->where('is_upah', 1)
                ->where('id', '!=', $request->id) // Pastikan tidak memeriksa ID yang sedang diupdate
                ->exists();
    
            if ($exists) {
                return redirect()->route('editUpah', ['id' => $request->id])
                    ->with('failed', 'Jabatan yang sama sudah ada di database');
            }
        }

        // Update data di tabel upah
        $update = DB::table('upah')->where('id', $request->id)->update([
            'jabatan' => $request->jabatan,
            'upah' => $request->upah,
            'is_upah' => $request->is_upah,
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
