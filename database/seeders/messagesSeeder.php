<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class messagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
           ['type'=> 'text','content'=>'Hello Mate','user_id'=>1,'room_id'=>'12614ccb988968e','created_at'=>NOW(),'updated_at'=>NOW()],
           ['type'=> 'text','content'=>'Hello,How you doing','user_id'=>2,'room_id'=>'12614ccb988968e','created_at'=>NOW(),'updated_at'=>NOW()],
           ['type'=> 'text','content'=>'fine','user_id'=>1,'room_id'=>'12614ccb988968e','created_at'=>NOW(),'updated_at'=>NOW()]
        ];
        DB::table('messages')
           ->insert($data);
    }
}
