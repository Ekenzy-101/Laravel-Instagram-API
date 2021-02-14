<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public $token;

    public $authUser;

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->only("email"), [
            "email" => ['required', 'email'],
        ]);

        if($validator->fails()) {
            return response()->json("Email is not valid", 400);
        }

        $status = Password::sendResetLink($request->only("email"));

        return $status === Password::RESET_LINK_SENT
                ? response()->json("Success")
                : response()->json(__($status), 400);
    }

    public function resetPassword(Request $request)
    {
        $inputs = $request->only("email", "password", "password_confirmation", "token");
        $validator = Validator::make($inputs, [
            "email" => ["required", "email"],
            "token" => ["required"],
            "password" => ["required", "min:6", "confirmed"]
        ]);

        if($validator->fails()) {
            if ($validator->errors()->has("email")) {
                return response()->json($validator->errors()->first("email"), 400);
            }
            if ($validator->errors()->has("password")) {
                return response()->json($validator->errors()->first("password"), 400);
            }
            if ($validator->errors()->has("token")) {
                return response()->json($validator->errors()->first("token"), 400);
            }
        }

        $status = Password::reset(
            $inputs,
            function ($user, $password) use ($request) {
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->save();

                $user->sendPasswordConfirmationNotification();

                $this->token = Auth::login($user);

                $followers = [];
                $following = [];

                foreach (Auth::user()->followers as $follower) {
                    array_push($followers, collect($follower)->only("id")->all());
                }

                foreach (Auth::user()->following as $followingUser) {
                    array_push($following, collect($followingUser)->only("id")->all());
                }

                $this->authUser = collect(Auth::user())->only(["id", "username", "image_url", "name"])->all();
                $this->authUser["following"] = $following;
                $this->authUser["followers"] = $followers;

                event(new PasswordReset($user));
            });

        $secure = App::environment("production");

        return $status === Password::PASSWORD_RESET
                ? response($this->authUser)->cookie('token', $this->token, null, "/", null, $secure, true)
                : response()->json(__($status), 400);
    }
}
