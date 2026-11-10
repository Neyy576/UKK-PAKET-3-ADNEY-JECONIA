<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Resmi
        User::create([
            'name' => 'Admin', 
            'email' => 'admin@perpus.test', 
            'password' => Hash::make('password'), 
            'role' => 'admin'
        ]);
        
        // 2. Akun Siswa untuk Tes Tombol Pinjam
        User::create([
            'name' => 'Dimas', 
            'email' => 'siswa@perpus.test', 
            'password' => Hash::make('password'), 
            'role' => 'siswa', 
            'kelas' => 'XII RPL 2'
        ]);

        // 3. Data Awal Buku Perpustakaan
        Book::create(['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'publisher' => 'Bentang', 'year' => 2005, 'stock' => 4]);
        Book::create(['title' => 'Bumi', 'author' => 'Tere Liye', 'publisher' => 'Gramedia', 'year' => 2014, 'stock' => 3]);
        Book::create(['title' => 'Belajar Laravel', 'author' => 'Budi Raharjo', 'publisher' => 'Informatika', 'year' => 2023, 'stock' => 5]);
    }
}
