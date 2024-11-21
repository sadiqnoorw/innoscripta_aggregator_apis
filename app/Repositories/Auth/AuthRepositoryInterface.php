<?php

namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    public function registerUser(array $data);
    public function loginUser(array $credentials);
    public function logoutUser();
    public function resetPassword(array $data);
}
