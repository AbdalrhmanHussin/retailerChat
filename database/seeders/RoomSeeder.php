<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')
           ->insert([
            'room_unique' => '12614ccb988968e',
            'user_id' => 1,
            'created_at'  => NOW(),
            'updated_at'  => NOW()
           ]);
    }
}
