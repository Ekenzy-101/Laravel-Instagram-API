<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->except('login', 'register', 'verifyEmail');
    }

    public function register(Request $request) {
        $validator = Validator::make($request->only("email", "password", "username", "name"), [
            'name' => ['required', 'max:50'],
            'username' => ['required', 'between:6,30', 'regex:/^[a-z0-9._]+$/', 'unique:ig_users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:ig_users,email'],
            'password' => ['required', 'min:6'],

        ]);

        if($validator->fails()) {
            $errors = $validator->errors();

            $email = $errors->first("email");
            $password = $errors->first("password");
            $username = $errors->first("username");
            $name = $errors->first("name");

            return response()->json(compact("email", "password", "username", "name"), 400);
        }

        $user = User::create([
            "id" => Str::orderedUuid()->toString(),
            'name' => $request->name,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'verification_code' => mt_rand(100000, 999999)
        ]);

        // Dispatch an event to send a verification email to the user
        event(new Registered($user));

        return response()->json("Success");
    }

    public function login(Request $request) {

        $validator = Validator::make($request->only("email", "password"), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json('Invalid Email and Password', 400);
        }

        $user = User::firstWhere('email', $request->email);

        if (!$user) {
            return response()->json('Invalid Email and Password', 400);
        }

        if (!$user->email_verified_at) {
            return response()->json('Email not verified', 400);
        }

        $token = Auth::attempt($validator->validated());

        if (!$token) {
            return response()->json('Invalid Email and Password', 400);
        }

        $user = collect(Auth::user())->only(["id", "email"])->all();

        return response($user)->cookie('token', $token, null, "/", null, null, true);
    }

    public function logout() {

        Auth::logout(true);

        return response()->json('Success')->withoutCookie("token", "/");
    }

    public function verifyEmail(Request $request) {
        $code = $request->only("code");

        $validator = Validator::make($code, [
            'code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json('Invalid Verification Code', 400);
        }

        $user = User::firstWhere('verification_code', '=', $code);

        if (!$user) {
            return response()->json('Invalid Verification Code', 400);
        }

        // Verify email
        $user->email_verified_at = now()->toDateTimeString();
        $user->save();

        // Generate token with the user information
        $token = Auth::login($user);

        $user = collect($user)->only(["id", "email"])->all();

        return response($user)->cookie('token', $token, null, "/", null, null, true);;
    }
}
