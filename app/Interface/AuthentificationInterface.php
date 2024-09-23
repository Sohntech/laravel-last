<?php

namespace App\Interface;
use Illuminate\Http\Request;

interface AuthentificationInterface
{
    public function authentificate(Request $request);
    public function logout();
}
    