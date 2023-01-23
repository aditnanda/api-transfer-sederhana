<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'email' => 'aditya.nanda0030@gmail.com',
            'password' => Hash::make("12345678"),
            'name' => 'Aditya Nanda Utama',
        ]);

        User::create([
            'email' => 'user@boscod.com',
            'password' => Hash::make("rahasia"),
            'name' => 'PT Bos COD Indonesia',
        ]);
    }
}
