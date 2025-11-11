<?php
namespace App\Controllers;

class AboutController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Tentang Kami';
        
        // Data kelompok mahasiswa
        $data['kelompok'] = [
            'nama_kelompok' => 'Kelompok 5',
            'prodi' => 'Akuntansi',
            'angkatan' => '60',
            'institusi' => 'Sekolah Vokasi IPB University',
        ];
        
        // Data anggota kelompok
        $data['anggota'] = [
            [
                'nama' => 'Muhammad Rafif Alexander Putra',
                'nim' => 'J3C122001',
                'role' => 'Ketua Kelompok',
                'foto' => base_url('assets/img/team/rafif.svg'),
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nim' => 'J3C122002',
                'role' => 'Sekretaris',
                'foto' => base_url('assets/img/team/bahlil1.jpg'),
            ],
            [
                'nama' => 'Ahmad Fauzi Rahman',
                'nim' => 'J3C122003',
                'role' => 'Bendahara',
                'foto' => base_url('assets/img/team/ahmad.svg'),
            ],
            [
                'nama' => 'Dewi Lestari',
                'nim' => 'J3C122004',
                'role' => 'Anggota',
                'foto' => base_url('assets/img/team/dewi.svg'),
            ],
            [
                'nama' => 'Budi Santoso',
                'nim' => 'J3C122005',
                'role' => 'Anggota',
                'foto' => base_url('assets/img/team/budi.svg'),
            ],
        ];
        
        return view('about/index', $data);
    }
}
