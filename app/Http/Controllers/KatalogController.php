<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index()
    {
        $produk = [
            ['id'=>1,'nama'=>'Semen','harga'=>60000],
            ['id'=>2,'nama'=>'Pasir','harga'=>250000],
            ['id'=>3,'nama'=>'Batu Bata','harga'=>1000],
            ['id'=>4,'nama'=>'Besi','harga'=>120000],
            ['id'=>5,'nama'=>'Cat Tembok','harga'=>150000],
        ];

        return view('katalog.index', compact('produk'));
    }

    public function show($id)
    {
        return "Menampilkan detail produk dengan ID: " . $id;
    }
}
{
    //
}
