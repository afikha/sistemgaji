<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\AdminGajiTenunController;
use App\Http\Controllers\AdminUpahController;

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
Route::get('/datatenun/datagajitenun', [AdminGajiTenunController::class, 'index'])->name('indexGajiTenun');
Route::get('/datatenun/add', [AdminGajiTenunController::class, 'add'])->name('addViewGajiTenun');
Route::post('/datatenun/add', [AdminGajiTenunController::class, 'create'])->name('addGajiTenun');
Route::get('/datatenun/edit/{id}', [AdminGajiTenunController::class, 'edit'])->name('editGajiTenun');
Route::post('/datatenun/update', [AdminGajiTenunController::class, 'update'])->name('updateGajiTenun');
Route::get('/datatenun/delete/{id}', [AdminGajiTenunController::class, 'delete'])->name('deleteGajiTenun');


// =================================================================================================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/databarang/datagajibarang', function () {return view('databarang/datagajibarang');});
Route::get('/databarang/add', function () {return view('databarang/add');});
Route::get('/databarang/edit', function () {return view('databarang/edit');});

