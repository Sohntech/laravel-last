<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use App\Interface\AuthentificationInterface;

class AuthService
{
    protected $authService;

    public function __construct()
    {
        $driver = env('AUTH_DRIVER', 'passport');

        $this->authService = ($driver === 'firebase') ? app(AuthentificationFirebase::class) : app(AuthentificationLocalPassport::class);
    }

    public function authentificate(Request $request)
    {
        return $this->authService->authentificate($request);
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
