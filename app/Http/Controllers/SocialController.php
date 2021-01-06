<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function facebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'max:50'],
            'image_url' => ['required', 'url']
        ]);

        if($validator->fails()) {
            return response()->json('Invalid Credientials', 400);
        }

        $user = User::firstWhere('email', strtolower($request->email));

        if ($user) {
        } else {
            $random_no = (string) mt_rand(10, 9999);
            $username = explode("@", $request->email)[0] . $random_no;

            $user = User::create([
                "id" => Str::orderedUuid()->toString(),
                'name' => $request->name,
                'email' => $request->email,
                'username' => $username,
                'email_verified_at' => now()->toDateTimeString()
            ]);
        }

        $token = Auth::login($user);
        $user = collect(Auth::user())->only(["id", "username"])->all();

        return response($user)->cookie('token', $token, null, "/", null, null, true);
    }
}
