<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $username = $request->query("search");

        if(!$username) {
            return response()->json([]);
        }

        $users = User::select('name', 'username', 'image_url', 'id')
        ->where('username', 'ilike', "%{$username}%")
        ->orWhere('name', 'ilike', "%{$username}%")->get();

        return response()->json($users);
    }
}
