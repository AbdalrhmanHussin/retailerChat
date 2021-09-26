<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $dummyUsers = [ 
            ['name'  => 'Doris Brown',
            'email' => 'DorBrown43@gmail.com',
            'password' => Hash::make('password'),
            'image' => 'user1.jpg'
            ],
            [
            'name'  => 'Mark Messer',
            'email' => 'MarkTheGreat201@gmail.com',
            'password' => Hash::make('password'),
            'image' => 'user2.jpg'
            ],
            [
            'name'  => 'Jone Adam',
            'email' => 'JonAdam43@gmail.com',
            'password' => Hash::make('password'),
            'image' => 'user3.jpg'
            ],
            [
            'name'  => 'Jone Adam',
            'email' => 'KenAdam43@gmail.com',
            'password' => Hash::make('password'),
            'image' => 'user4.jpg'
            ] 
        ];
       DB::table('users')->insert($dummyUsers);
    }
}
