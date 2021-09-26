<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\result;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function Users() 
    {
        $users = User::limit(10)->get();
        return result::repsonse(true,$users);
    }
   
    /**
     * Return a specific user
     */
    public function find(User $user)
    {
        return result::repsonse(true,$user);
    }
}
