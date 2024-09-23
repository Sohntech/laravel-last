<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Services\FirebaseAuthGuard;
use Kreait\Firebase\Factory;  // Importation du bon service Firebase

class FirebaseAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::extend('firebase', function ($app, $name, array $config) {
            // Crée une instance de Firebase Auth via la Factory de Kreait
            $firebaseAuth = (new Factory)
                ->withServiceAccount(config('services.firebase.credentials'))
                ->createAuth();

            return new FirebaseAuthGuard($firebaseAuth, $app['request']);
        });
    }

    public function register()
    {
        //
    }
}
