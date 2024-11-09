<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/datakaryawan', function () {return view('datakaryawan');});
Route::get('/add', function () {return view('add');});
Route::get('/edit', function () {return view('edit');});

Route::get('/datatenun/datagajitenun', function () {return view('datatenun/datagajitenun');});
Route::get('/datatenun/add', function () {return view('datatenun/add');});
Route::get('/datatenun/edit', function () {return view('datatenun/edit');});

Route::get('/databarang/datagajibarang', function () {return view('databarang/datagajibarang');});
Route::get('/databarang/add', function () {return view('databarang/add');});
Route::get('/databarang/edit', function () {return view('databarang/edit');});