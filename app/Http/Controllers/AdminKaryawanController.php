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
            return view('edit', ['data' => $data]);
    }

    // public function delete($id)
    // {
    //     $image_url = DB::table('karyawan')->where('id', $id)->first();
    //     $delete = DB::table('karyawan')->where('id', $id)->delete();

    //     return redirect()->route('staff.indexSoloEvent')
    //         ->with('success', 'Data berhasil dihapus!');
    // }

}
