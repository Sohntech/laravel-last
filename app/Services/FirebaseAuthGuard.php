<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Guard;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use App\Models\User;

class FirebaseAuthGuard implements Guard
{
    protected $request;
    protected $firebaseAuth;
    protected $user;

    public function __construct(FirebaseAuth $firebaseAuth, Request $request)
    {
        $this->firebaseAuth = $firebaseAuth;
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (!$token) {
            return null;
        }

        try {
            $verifiedToken = $this->firebaseAuth->verifyIdToken($token);
            $firebaseUser = $this->firebaseAuth->getUser($verifiedToken->claims()->get('sub'));

            // Chercher l'utilisateur correspondant dans la base de données par email (ou un autre champ unique)
            $this->user = User::where('email', $firebaseUser->email)->first();

            // S'assurer que l'utilisateur a bien un rôle
            if ($this->user && $this->user->role) {
                return $this->user;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function validate(array $credentials = [])
    {
        return true;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->user() ? $this->user()->id : null;
    }

    public function hasUser()
    {
        return !is_null($this->user);
    }

    public function setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $this->user = $user;
    }
}
