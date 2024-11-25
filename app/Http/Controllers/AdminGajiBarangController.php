<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminGajiBarangController extends Controller
{
    public function index($karyawan_id)
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $minggu = request('minggu');
            $tahun = request('tahun');
            $jabatangaji = DB::table('karyawan')
                ->join('upah', 'karyawan.jabatan_karyawan', '=', 'upah.jabatan')
                ->where('karyawan.id', $karyawan_id)->first()->upah;
            if ($minggu == null && $tahun == null) {
                $data = DB::table('gajibarang')
                    ->where('karyawan_id', $karyawan_id)
                    ->get();
            } else if ($minggu != null && $tahun == null) {
                $data = DB::table('gajibarang')
                    ->where('karyawan_id', $karyawan_id)
                    ->where('minggu', $minggu)
                    ->get();
            } else if ($tahun != null && $minggu == null) {
                $data = DB::table('gajibarang')
                    ->where('karyawan_id', $karyawan_id)
                    ->whereYear('tanggal', $tahun)
                    ->get();
            } else {
                $data = DB::table('gajibarang')
                    ->where('karyawan_id', $karyawan_id)
                    ->whereYear('tanggal', $tahun)
                    ->where('minggu', $minggu)
                    ->get();
            }

            $gaji = 0;
            foreach ($data as $d) {
                $gaji = $gaji + ($d->total_pengerjaan * $jabatangaji);
            }
            return view('databarang.datagajibarang', ['data' => $data, 'gaji' => $gaji, 'karyawan_id' => $karyawan_id, 'username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function add()
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            $karyawan_id = request('karyawan_id');
            return view('databarang.add',  ['karyawan_id' => $karyawan_id, 'username' => $username]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function create(Request $request)
    {
        $tanggal = $request->tanggal;
        $minggu = $request->minggu;
        $barangmasuk = $request->barangmasuk;
        $barangkeluar = $request->barangkeluar;
        $sisabahan = $request->sisabahan;
        $totaldikerjakan = $request->totaldikerjakan;
        $karyawan_id = $request->karyawan_id;

        //Cek Tanggal <= hari ini ndak
        $cektanggal = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->first();
        if ($tanggal < date('Y-m-d')) {
            return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal tidak boleh kurang dari hari ini!');
        }
        if (isset($cektanggal->tanggal) == $tanggal) {
            return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal yang anda masukkan sudah ada!');
        }

        $add = DB::table('gajibarang')->insert([
            'tanggal' => $tanggal,
            'minggu' => $minggu,
            'barang_jadi' => $barangmasuk,
            'barang_proses' => $barangkeluar,
            'sisabahan_proses' => $sisabahan,
            'total_pengerjaan' => $totaldikerjakan,
            'karyawan_id' => $karyawan_id
        ]);

        if ($add) {
            return redirect()->route('indexGajiBarang', $karyawan_id)
                ->with('success', 'Data gaji barang berhasil ditambahkan!');
        } else {
            return redirect()->route('indexGajiBarang', $karyawan_id)
                ->with('failed', 'Data gaji barang gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->name) {
            $data = DB::table('gajibarang')
                ->where('id', $id)
                ->first();
            return view('databarang.edit', ['data' => $data, 'username' => Auth::user()->name]);
        } else {
            return redirect()->route('indexLogin')->with('error', 'Silakan Login');
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $tanggal = $request->tanggal;
        $barangmasuk = $request->barangmasuk;
        $barangkeluar = $request->barangkeluar;
        $sisabahan = $request->sisabahan;
        $totaldikerjakan = $request->totaldikerjakan;
        $karyawan_id = $request->karyawan_id;

        //dd($request->all());

        $update = DB::table('gajibarang')->where('id', $id)->update([
            'tanggal' => $tanggal,
            'barang_jadi' => $barangmasuk,
            'barang_proses' => $barangkeluar,
            'sisabahan_proses' => $sisabahan,
            'total_pengerjaan' => $totaldikerjakan
        ]);

        if ($update) {
            return redirect()->route('indexGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('success', 'Data gaji barang berhasil diupdate!');
        } else {
            return redirect()->route('indexGajiBarang', ['id' => $id])
                ->with('failed', 'Gagal mengupdate data gaji barang.');
        }
    }

    public function delete($id)
    {
        $gajibarangData = DB::table('gajibarang')->where('id', $id)->first();
        $delete = DB::table('gajibarang')->where('id', $id)->delete();

        return redirect()->route('indexGajiBarang', $gajibarangData->karyawan_id)
            ->with('success', 'Data berhasil dihapus!');
    }
}
