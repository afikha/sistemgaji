<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminKaryawanController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/datakaryawan', function () {return view('datakaryawan');});
// Route::get('/add', function () {return view('add');});
// Route::get('/edit', function () {return view('edit');});

Route::get('/contoh', function () {return view('contoh');});

Route::get('/datatenun/datagajitenun', function () {return view('datatenun/datagajitenun');});
Route::get('/datatenun/add', function () {return view('datatenun/add');});
Route::get('/datatenun/edit', function () {return view('datatenun/edit');});


Route::get('/databarang/datagajibarang', function () {return view('databarang/datagajibarang');});
Route::get('/databarang/add', function () {return view('databarang/add');});
Route::get('/databarang/edit', function () {return view('databarang/edit');});

// Route::group(['prefix' => 'user', 'middleware' => ['auth'], 'as' => 'staff.'], function () {
//     Route::get('/datakaryawan', [AdminKaryawanController::class, 'index'])->name('indexKaryawan');
//     Route::get('/add', [AdminKaryawanController::class, 'add'])->name('addViewKaryawan');
//     Route::get('/add', [AdminKaryawanController::class, 'create'])->name('addKaryawan');
// });

//route yang benar
Route::get('/datakaryawan', [AdminKaryawanController::class, 'index'])->name('indexKaryawan');
Route::get('/add', [AdminKaryawanController::class, 'add'])->name('addViewKaryawan');
Route::post('/add', [AdminKaryawanController::class, 'create'])->name('addKaryawan');
Route::get('/edit/{id}', [AdminKaryawanController::class, 'edit'])->name('editKaryawan');
Route::post('/update', [AdminKaryawanController::class, 'update'])->name('updateKaryawan');
Route::get('/delete/{id}', [AdminKaryawanController::class, 'delete'])->name('deleteKaryawan');
Route::get('/datakaryawan/{id}', [AdminKaryawanController::class, 'datakaryawan'])->name('datakaryawan');