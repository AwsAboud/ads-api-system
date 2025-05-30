<?php

namespace App\Services;

use App\Jobs\sendEmailToUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user and return the user with an API token.
     *
     * @param  array  $data
     * @return array
     */
    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        sendEmailToUser::dispatch($user);
        
        return [
            'user' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
        ];
    }

    /**
     * Attempt to authenticate the user and return an API token.
     *
     * @param  array  $credentials
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Wrong credentials'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
        ];
    }

    /**
     * Revoke the current access token.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Revoke all access tokens for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function logoutFromAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }

}