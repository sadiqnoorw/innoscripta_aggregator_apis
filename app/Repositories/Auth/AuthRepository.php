<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthRepository implements AuthRepositoryInterface
{
    public function registerUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function loginUser(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $user->createToken('API Token')->plainTextToken;
        }
        return false;
    }

    public function logoutUser()
    {
        Auth::user()->tokens()->delete();
    }

    public function resetPassword(array $data)
    {
        $status = Password::sendResetLink(['email' => $data['email']]);
        return $status;
    }
}
