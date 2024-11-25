<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\AdminGajiTenunController;
use App\Http\Controllers\AdminGajiBarangController;
use App\Http\Controllers\AdminUpahController;
use App\Http\Controllers\AuthController;

//Auth
Route::get('/login', [AuthController::class, 'index'])->name('indexLogin');
Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//route karyawan
Route::get('/datakaryawan', [AdminKaryawanController::class, 'index'])->name('indexKaryawan');
Route::get('/add', [AdminKaryawanController::class, 'add'])->name('addViewKaryawan');
Route::post('/add', [AdminKaryawanController::class, 'create'])->name('addKaryawan');
Route::get('/edit/{id}', [AdminKaryawanController::class, 'edit'])->name('editKaryawan');
Route::post('/update', [AdminKaryawanController::class, 'update'])->name('updateKaryawan');
Route::get('/delete/{id}', [AdminKaryawanController::class, 'delete'])->name('deleteKaryawan');
Route::get('/datakaryawan/{id}', [AdminKaryawanController::class, 'datakaryawan'])->name('datakaryawan');

//route upah karyawan
Route::get('/upah/upah', [AdminUpahController::class, 'index'])->name('indexUpah');
Route::get('/upah/add', [AdminUpahController::class, 'add'])->name('addViewUpah');
Route::post('/upah/add', [AdminUpahController::class, 'create'])->name('addUpah');
Route::get('/upah/edit/{id}', [AdminUpahController::class, 'edit'])->name('editUpah');
Route::post('/upah/update', [AdminUpahController::class, 'update'])->name('updateUpah');
Route::get('/upah/delete/{id}', [AdminUpahController::class, 'delete'])->name('deleteUpah');

// route gaji tenun
Route::get('/datatenun/datagajitenun/{karyawan_id}', [AdminGajiTenunController::class, 'index'])->name('indexGajiTenun');
Route::get('/datatenun/add', [AdminGajiTenunController::class, 'add'])->name('addViewGajiTenun');
Route::post('/datatenun/add', [AdminGajiTenunController::class, 'create'])->name('addGajiTenun');
Route::get('/datatenun/edit/{id}', [AdminGajiTenunController::class, 'edit'])->name('editGajiTenun');
Route::post('/datatenun/update', [AdminGajiTenunController::class, 'update'])->name('updateGajiTenun');
Route::get('/datatenun/delete/{id}', [AdminGajiTenunController::class, 'delete'])->name('deleteGajiTenun');

// route gaji barang
Route::get('/databarang/datagajibarang/{karyawan_id}', [AdminGajiBarangController::class, 'index'])->name('indexGajiBarang');
Route::get('/databarang/add', [AdminGajiBarangController::class, 'add'])->name('addViewGajiBarang');
Route::post('/databarang/add', [AdminGajiBarangController::class, 'create'])->name('addGajiBarang');
Route::get('/databarang/edit/{id}', [AdminGajiBarangController::class, 'edit'])->name('editGajiBarang');
Route::post('/databarang/update', [AdminGajiBarangController::class, 'update'])->name('updateGajiBarang');
Route::get('/databarang/delete/{id}', [AdminGajiBarangController::class, 'delete'])->name('deleteGajiBarang');

// =================================================================================================================
Route::get('/', function () {
    return view('welcome');
});
