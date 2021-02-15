<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
                'image_url' => $request->image_url,
                'name' => $request->name,
                'email' => $request->email,
                'username' => $username,
                'email_verified_at' => now()->toDateTimeString()
            ]);
        }

        $token = Auth::login($user);
        $followers = [];
        $following = [];

        foreach ($user->followers as $follower) {
            array_push($followers, collect($follower)->only("id")->all());
        }

        foreach ($user->following as $followingUser) {
            array_push($following, collect($followingUser)->only("id")->all());
        }

        $user = collect(Auth::user())->only(["id", "username", "image_url", "name"])->all();
        $user["following"] = $following;
        $user["followers"] = $followers;

        $secure = App::environment("production");
        return response($user)->cookie('token', $token, null, "/;samesite=none", null, $secure, true);
    }
}
