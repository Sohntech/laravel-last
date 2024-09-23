<?php



namespace App\Http\Controllers;

use App\Interface\AuthentificationInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthentificationInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        return $this->authService->authentificate($request);
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
