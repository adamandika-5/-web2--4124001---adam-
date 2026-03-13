<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $data = [
            'nama' => 'Aurel Prayoga Arandy',
            'nim' => '4124027',
            'prodi' => 'Sisitem Informasi',
            'semester' => 4,
            'keahlian' => ['Laravel', 'HTML', 'CSS', 'JavaScript']
        ];

        return view('profil', $data);
    }

    public function show($nim)
    {
        return "Menampilkan profil dengan NIM: " . $nim;
    }
}
{
    //
}
