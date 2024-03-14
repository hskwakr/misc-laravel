<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TokenSetupService
{
    private array $credentials = ['email' => '', 'password' => ''];

    public function __construct(array $credentials = ['email' => '', 'password' => ''])
    {
        $this->credentials = $credentials;
    }

    public function create()
    {
        if (!Auth::attempt($this->credentials)) {
            $user = new \App\Models\User();

            $user->name = 'Admin';
            $user->email = $this->credentials['email'];
            $user->password = Hash::make($this->credentials['password']);

            $user->save();

            if (Auth::attempt($this->credentials)) {
                $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
                $updateToken = $user->createToken('update-token', ['create', 'update']);
                $basicToken = $user->createToken('basic-token');

                return [
                    'admin' => $adminToken->plainTextToken,
                    'update' => $updateToken->plainTextToken,
                    'basic' => $basicToken->plainTextToken,
                ];
            }
        }
    }
}
