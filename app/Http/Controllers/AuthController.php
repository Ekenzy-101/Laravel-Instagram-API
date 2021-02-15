<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
            'username' => ['required',
            'regex:/^([a-z0-9_])([a-z0-9_.])+([a-z0-9_])$/', 'unique:ig_users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:ig_users,email'],
            'password' => ['required', 'min:6'],

        ], [
            'email.unique' => "Another account is using :input",
            'username.unique' => "This username isn't available. Please try another.",
            'username.regex' => "Usernames can only use letters, numbers, underscores and periods."
        ]);

        if($validator->fails()) {
            if ($validator->errors()->has("email")) {
                return response()->json($validator->errors()->first("email"), 400);
            }
            if ($validator->errors()->has("username")) {
                return response()->json($validator->errors()->first("username"), 400);
            }
            if ($validator->errors()->has("password")) {
                return response()->json($validator->errors()->first("password"), 400);
            }
            if ($validator->errors()->has("name")) {
                return response()->json($validator->errors()->first("name"), 400);
            }
        }

        $user = User::create([
            "id" => Str::orderedUuid()->toString(),
            'name' => $request->name,
            'email' => strtolower($request->email),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'verification_code' => mt_rand(100000, 999999)
        ]);

        // Dispatch an event to send a verification email to the user
        try {
            event(new Registered($user));
        } catch (\Throwable $th) {
            return response()->json("An unexpected error occured", 500);
        }

        return response()->json("Success", 201);
    }

    public function login(Request $request) {

        $validator = Validator::make($request->only("email", "password"), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json('Your credentials are invalid', 400);
        }

        $user = User::firstWhere('email', strtolower($request->email));

        if (!$user) {
            return response()->json("The email you entered doesn't belong to an account. Please check your email and try-again", 400);
        }

        if (!$user->email_verified_at) {
            return response()->json("Your account has not been verified", 400);
        }

        $token = Auth::attempt($validator->validated());

        if (!$token) {
            return response()->json('Sorry, your password was incorrect, Please double-check your password', 400);
        }

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
        return response($user)->cookie('token', $token, null, "/", null, $secure, true, false, "none");
    }

    public function logout() {

        Auth::logout(true);

        $secure = App::environment("production");
        $cookie = cookie('token', "", -1, "/", null, $secure, true, false, "none");
        return response()->json('Success')->withCookie($cookie);
    }

    public function verifyEmail(Request $request) {
        $validator = Validator::make($request->only("code", "email"), [
            'code' => ['required', 'digits:6'],
            'email' => ['required','email'],
        ]);

        if ($validator->fails()) {
            return response()->json('Invalid Verification Code', 400);
        }

        $user = User::firstWhere('email', strtolower($request->email));

        if (!$user || ($user->verification_code !== $request->code)) {
            return response()->json('Invalid Verification Code', 400);
        }

        // Verify email
        $user->email_verified_at = now()->toDateTimeString();
        $user->save();

        // Generate token with the user information
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
        return response($user)->cookie('token', $token, null, "/", null, $secure, true, false, "none");;
    }
}
