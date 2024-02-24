<?php

namespace Modules\Auth\Services;

use Core\Services\Configurable;
use Core\Services\Identifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\PersonalAccessTokenResult;
use Modules\Auth\Contracts\AuthService as ContractsAuthService;
use Modules\User\Facades\UserRepository;

class AuthService implements ContractsAuthService
{
    use Configurable, Identifiable;

    /**
     * Login and generate access token.
     *
     * @param array $request
     * @return PersonalAccessTokenResult
     */
    public function login(array $request): PersonalAccessTokenResult
    {
        $user = UserRepository::where('email', $request['email'])->first();

        if (! $user || ! Hash::check($request['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => [trans('response.invalid_credentials')],
            ]);
        }

        return $user->createToken($user->name);
    }

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void
    {
        $user = Auth::user();

        // Revoke all tokens associated with the user
        $user->tokens->each(function ($token) {
            $token->revoke();
        });
    }
}
