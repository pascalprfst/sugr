<?php

use App\Models\Groceries;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;

Route::middleware(\App\Http\Middleware\APIMiddleware::class)->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/groceries', function (Request $request) {
            $groceries = Groceries::all();

            return $groceries;
        });

        Route::get('/groceries/{search}', function ($search) {
            $groceries = Groceries::where('name', 'like', "%{$search}%")->get();

            return $groceries;
        });

        Route::post('/register', function (Request $request) {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => "User successfully registered",
                'user' => $user,
            ], 200);

        });

        Route::post('/login', function (Request $request) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {

                return response()->json([
                    'message' => 'Successfully logged in',
                    'user' => Auth::user()
                ], 200);
            }

            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        });
    });
});

