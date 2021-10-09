<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class,'user_room','room_id','user_id')->orderBy('created_at');
    }

    public function messages()
    {
        return $this->hasMany(messages::class);
    }
}
