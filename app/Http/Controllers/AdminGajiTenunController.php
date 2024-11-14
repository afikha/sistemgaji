<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGajiTenunController extends Controller
{
    public function index() {
        $data = DB::table('gajitenun')->get();
        return view('datatenun/datagajitenun', ['data' => $data]);;
    }

    public function add()
    {
        return view('datatenun.add');
    }

    public function create(Request $request){
    // Mengambil data dari request
    $minggu = $request->minggu;
    $hari_1 = $request->hari_1;
    $hari_2 = $request->hari_2;
    $hari_3 = $request->hari_3;
    $hari_4 = $request->hari_4;
    $hari_5 = $request->hari_5;
    $hari_6 = $request->hari_6;
    $total_pengerjaan = $request->total_pengerjaan;
    $gaji = $request->gaji;

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
        'hari_1' => $hari_1,
        'hari_2' => $hari_2,
        'hari_3' => $hari_3,
        'hari_4' => $hari_4,
        'hari_5' => $hari_5,
        'hari_6' => $hari_6,
        'total_pengerjaan' => $total_pengerjaan,
        'gaji' => $gaji,
    ]);

        // Cek apakah data berhasil ditambahkan
        if ($add) {
            return redirect()->route('indexGajiTenun')
                ->with('success', 'Data gaji tenun berhasil ditambahkan!');
        } else {
            return redirect()->route('indexGajiTenun')
                ->with('failed', 'Data gaji tenun gagal ditambahkan!');
        }
    }

    public function edit($id) {
            $data = DB::table('gajitenun')
                ->where('id', $id)
                ->first();
            return view('datatenun.edit', ['data' => $data]);
    }

    public function update(Request $request){
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
    $minggu = $request->minggu;
    $hari_1 = $request->hari_1;
    $hari_2 = $request->hari_2;
    $hari_3 = $request->hari_3;
    $hari_4 = $request->hari_4;
    $hari_5 = $request->hari_5;
    $hari_6 = $request->hari_6;


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
            return redirect()->route('indexGajiTenun')
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

        return redirect()->route('indexGajiTenun')
            ->with('success', 'Data berhasil dihapus!');
    }
    


}
