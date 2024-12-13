<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $tanggal = $request->tanggal; // Tanggal input yang dimasukkan
        $minggu = $request->minggu;
        $barangmasuk = $request->barangmasuk;
        $barangkeluar = $request->barangkeluar;
        $sisabahan = $request->sisabahan;
        $totaldikerjakan = $request->totaldikerjakan;
        $karyawan_id = $request->karyawan_id;
    
        // Validasi jika tanggal atau minggu kosong
        if (is_null($tanggal) || is_null($minggu)) {
            return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal dan minggu harus diisi!');
        }
    
        // Dapatkan tahun dari tanggal yang dimasukkan
        $tahun = Carbon::parse($tanggal)->year;
    
        // Cek jumlah input untuk minggu tersebut per tahun (maksimal 6 input per minggu)
        $jumlahInputMingguan = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu)
            ->count();
    
        if ($jumlahInputMingguan >= 6) {
            return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Maksimal 6 data dapat diinputkan dalam 1 minggu pada tahun ini!');
        }
    
        // Cek apakah minggu sebelumnya sudah diinputkan jika minggu lebih dari 1
        if ($minggu > 1) {
            // Ambil tanggal pertama minggu sebelumnya (untuk minggu yang lebih dari 1)
            $tanggalMingguSebelumnya = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1) // Cek minggu sebelumnya
                ->orderBy('tanggal', 'asc') // Mengambil tanggal pertama minggu sebelumnya
                ->value('tanggal');
    
            if (!$tanggalMingguSebelumnya) {
                return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                    ->with('failed', "Minggu ke-" . ($minggu - 1) . " harus diinputkan terlebih dahulu sebelum minggu ke-{$minggu}.");
            }
    
            // Validasi jika tanggal minggu ke-{$minggu} kurang dari 6 hari setelah minggu sebelumnya
            if (Carbon::parse($tanggal) < Carbon::parse($tanggalMingguSebelumnya)->addDays(7)) {
                return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                    ->with('failed', "Tanggal untuk minggu ke-{$minggu} harus dimulai dari 7 hari setelah tanggal pertama minggu ke-" . ($minggu - 1) . " ({$tanggalMingguSebelumnya}).");
            }
        }
    
        // Ambil tanggal input pertama di minggu tersebut
        $tanggalAwalMinggu = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu)
            ->orderBy('tanggal', 'asc') // Ambil tanggal pertama di minggu
            ->value('tanggal');
    
        if ($tanggalAwalMinggu) {
            $rentangMulai = Carbon::parse($tanggalAwalMinggu);
            $rentangAkhir = $rentangMulai->copy()->addDays(7);
    
            // Validasi apakah tanggal berada dalam rentang minggu
            if (!Carbon::parse($tanggal)->between($rentangMulai, $rentangAkhir)) {
                return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                    ->with('failed', "Tanggal harus berada dalam rentang minggu ini ({$rentangMulai->format('Y-m-d')} hingga {$rentangAkhir->format('Y-m-d')})!");
            }
    
            // Validasi apakah tanggal lebih lampau dari tanggal sebelumnya dalam minggu yang sama
            $tanggalTerakhirMingguIni = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu)
                ->orderBy('tanggal', 'desc')
                ->value('tanggal');
    
            if ($tanggalTerakhirMingguIni && Carbon::parse($tanggal) < Carbon::parse($tanggalTerakhirMingguIni)) {
                return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                    ->with('failed', "Tanggal pada minggu ke-{$minggu} tidak boleh lebih lampau dari tanggal sebelumnya ({$tanggalTerakhirMingguIni}).");
            }
        }
    
        // Cek Tanggal <= hari ini ndak
        if ($tanggal > date('Y-m-d')) {
            return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal tidak boleh lebih dari hari ini!');
        }
    
        // Cek jika tanggal sudah ada dalam database untuk karyawan yang sama
        $cektanggal = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->where('tanggal', $tanggal)
            ->first();
    
        if ($cektanggal) {  
            return redirect()->route('addViewGajiBarang', ['karyawan_id' => $karyawan_id])
                ->with('failed', 'Tanggal yang Anda masukkan sudah ada!');
        }
    
        // Menambahkan data baru
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
        $minggu = $request->minggu;
        $barangmasuk = $request->barangmasuk;
        $barangkeluar = $request->barangkeluar;
        $sisabahan = $request->sisabahan;
        $totaldikerjakan = $request->totaldikerjakan;
        $karyawan_id = $request->karyawan_id;
    
        // Validasi jika tanggal atau minggu kosong
        if (is_null($tanggal) || is_null($minggu)) {
            return redirect()->route('editGajiBarang', ['id' => $id])
                ->with('failed', 'Tanggal dan minggu harus diisi!');
        }
    
        // Dapatkan tahun dari tanggal yang dimasukkan
        $tahun = Carbon::parse($tanggal)->year;
    
        // Cek jumlah input untuk minggu tersebut per tahun (tidak termasuk data yang sedang diupdate)
        $jumlahInputMingguan = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu)
            ->where('id', '!=', $id)
            ->count();
    
        if ($jumlahInputMingguan >= 6) {
            return redirect()->route('editGajiBarang', ['id' => $id])
                ->with('failed', 'Maksimal 6 data dapat diinputkan dalam 1 minggu pada tahun ini!');
        }
    
        // Cek apakah minggu sebelumnya sudah ada, tetapi hanya berlaku untuk minggu > 1
        if ($minggu > 1) {
            $cekMingguSebelumnya = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1)
                ->exists();
    
            if (!$cekMingguSebelumnya) {
                // Peringatan jika minggu sebelumnya belum ada
                return redirect()->route('editGajiBarang', ['id' => $id])
                    ->with('warning', "Minggu sebelumnya belum ada, apakah Anda yakin ingin mengedit minggu ke-{$minggu}?");
            }
    
            // Ambil tanggal terakhir dari minggu sebelumnya
            $tanggalTerakhirMingguSebelumnya = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu - 1)
                ->orderBy('tanggal', 'desc')
                ->value('tanggal');
    
            if (Carbon::parse($tanggal) < Carbon::parse($tanggalTerakhirMingguSebelumnya)) {
                return redirect()->route('editGajiBarang', ['id' => $id])
                    ->with('failed', "Tanggal pada minggu ke-{$minggu} tidak boleh lebih lampau dari tanggal terakhir pada minggu ke-" . ($minggu - 1) . " ({$tanggalTerakhirMingguSebelumnya}).");
            }
        } elseif ($minggu == 1) {
            // Jika minggu 1, cek apakah sudah ada input di minggu 1
            $tanggalAwalMinggu = DB::table('gajibarang')
                ->where('karyawan_id', $karyawan_id)
                ->whereYear('tanggal', $tahun)
                ->where('minggu', $minggu)
                ->orderBy('tanggal', 'asc')
                ->value('tanggal');
        
            if ($tanggalAwalMinggu) {
                // Jika ada data di minggu 1, validasi rentang tanggal
                $rentangMulai = Carbon::parse($tanggalAwalMinggu);
                $rentangAkhir = $rentangMulai->copy()->addDays(7);
        
                if (!Carbon::parse($tanggal)->between($rentangMulai, $rentangAkhir)) {
                    return redirect()->route('editGajiBarang', ['id' => $id])
                        ->with('failed', "Tanggal harus berada dalam rentang minggu ini ({$rentangMulai->format('Y-m-d')} hingga {$rentangAkhir->format('Y-m-d')})!");
                }
            } else {
                // Jika belum ada data di minggu 1, izinkan input sebagai penentu awal minggu
                // Tidak perlu validasi tambahan
            }
        }
        $tanggalAwalMinggu = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->whereYear('tanggal', $tahun)
            ->where('minggu', $minggu)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($tanggalAwalMinggu) {
            // Jika ada data di minggu 1, validasi rentang tanggal
            $rentangMulai = Carbon::parse($tanggalAwalMinggu);
            $rentangAkhir = $rentangMulai->copy()->addDays(7);

            if (!Carbon::parse($tanggal)->between($rentangMulai, $rentangAkhir)) {
                return redirect()->route('editGajiBarang', ['id' => $id])
                    ->with('failed', "Tanggal harus berada dalam rentang minggu ini ({$rentangMulai->format('Y-m-d')} hingga {$rentangAkhir->format('Y-m-d')})!");
            }
        } else {
            // Jika belum ada data di minggu 1, izinkan input sebagai penentu awal minggu
            // Tidak perlu validasi tambahan
        }
        
        // Cek Tanggal <= hari ini
        if ($tanggal > date('Y-m-d')) {
            return redirect()->route('editGajiBarang', ['id' => $id])
                ->with('failed', 'Tanggal tidak boleh lebih dari hari ini!');
        }
    
        // Cek jika tanggal sudah ada dalam database untuk karyawan yang sama (tidak termasuk data yang sedang diupdate)
        $cektanggal = DB::table('gajibarang')
            ->where('karyawan_id', $karyawan_id)
            ->where('tanggal', $tanggal)
            ->where('id', '!=', $id)
            ->first();
    
        if ($cektanggal) {
            return redirect()->route('editGajiBarang', ['id' => $id])
                ->with('failed', 'Tanggal yang Anda masukkan sudah ada!');
        }
    
        // Update data
        $update = DB::table('gajibarang')->where('id', $id)->update([
            'tanggal' => $tanggal,
            'minggu' => $minggu,
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
