<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PokemonController::class, 'pokedex'])->name('pokedex');
Route::get('/battle', [PokemonController::class, 'battle'])->name('battle');
Route::view('/guess', 'guess')->name('guess');

Route::get('/produk/2', function () {
    return response()->json([
        'id' => 2,
        'nama' => 'Produk 2',
        'harga' => 20000,
        'stok' => 10,
        'tag' => 'tag2',
        'deskripsi' => 'Deskripsi produk 2',
        'gambar' => 'gambar2.jpg',
    ]);
});

Route::get('/produk/1', function () {
    return response()->json([
        'id' => 1,
        'nama' => 'Produk 1',
        'harga' => 10000,
        'stok' => 5,
        'tag' => 'tag1',
        'deskripsi' => 'Deskripsi produk 1',
        'gambar' => 'gambar1.jpg',
    ]);
});
Route::get('/produk/3', function () {
    return response()->json([
        'id' => 3,
        'nama' => 'Produk 3',
        'harga' => 30000,
        'stok' => 15,
        'tag' => 'tag3',
        'deskripsi' => 'Deskripsi produk 3',
        'gambar' => 'gambar3.jpg',
    ]);
});
Route::get('/produk/4', function () {
    return response()->json([
        'id' => 4,
        'nama' => 'Produk 4',
        'harga' => 40000,
        'stok' => 20,
        'tag' => 'tag4',
        'deskripsi' => 'Deskripsi produk 4',
        'gambar' => 'gambar4.jpg',

    ]);
});

Route::get('produk/5', function () {
    return response()->json([
        'id' => 5,
        'nama' => 'Produk 5',
        'harga' => 50000,
        'stok' => 0,
        'tag' => 'tag5',
        'deskripsi' => 'Deskripsi produk 5',
        'gambar' => 'gambar5.jpg',
    ]);
});
Route::get('/produk', function () {
    return response()->json([
        [
            'id' => 1,
            'nama' => 'Produk 1',
            'harga' => 10000,
            'stok' => 5,
            'tag' => 'tag1',
            'deskripsi' => 'Deskripsi produk 1',
            'gambar' => 'gambar1.jpg',
        ],
        [
            'id' => 2,
            'nama' => 'Produk 2',
            'harga' => 20000,
            'stok' => 10,
            'tag' => 'tag2',
            'deskripsi' => 'Deskripsi produk 2',
            'gambar' => 'gambar2.jpg',
        ],
        [
            'id' => 3,
            'nama' => 'Produk 3',
            'harga' => 30000,
            'stok' => 15,
            'tag' => 'tag3',
            'deskripsi' => 'Deskripsi produk 3',
            'gambar' => 'gambar3.jpg',
        ],
        [
            'id' => 4,
            'nama' => 'Produk 4',
            'harga' => 40000,
            'stok' => 20,
            'tag' => 'tag4',
            'deskripsi' => 'Deskripsi produk 4',
            'gambar' => 'gambar4.jpg',
        ],
        [
            'id' => 5,
            'nama' => 'Produk 5',
            'harga' => 50000,
            'stok' => 0,
            'tag' => 'tag5',
            'deskripsi' => 'Deskripsi produk 5',
            'gambar' => 'gambar5.jpg',
        ],
    ]);
});
