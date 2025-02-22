<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('master_user')->insert([
            [
                'user' => 'admin',
                'description' => 'Administrator',
                'password' => Hash::make('admin123'),
                'username' => 'admin',
                'phone' => '081234567890',
                'nik' => '1234567890',
                'departmentid' => 1,
                'unitid' => 1,
                'entryuser' => 'system',
                'entryip' => '127.0.0.1',
                'updatetime' => now(),
                'updateuser' => 'system',
                'updateip' => '127.0.0.1',
                'avatar' => null,
            ],
            [
                'user' => 'johndoe',
                'description' => 'Regular User',
                'password' => Hash::make('user123'),
                'username' => 'johndoe',
                'phone' => '081298765432',
                'nik' => '0987654321',
                'departmentid' => 2,
                'unitid' => 2,
                'entryuser' => 'system',
                'entryip' => '127.0.0.1',
                'updatetime' => now(),
                'updateuser' => 'system',
                'updateip' => '127.0.0.1',
                'avatar' => null,
            ],
        ]);
    }
}
