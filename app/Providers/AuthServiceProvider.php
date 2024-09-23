<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Interface\AuthentificationInterface;
use App\Services\Auth\AuthentificationLocalPassport; // Assurez-vous d'importer la bonne classe
use App\Services\Auth\AuthentificationFirebase;
use App\Models\User;
use App\Policies\UserPolicy;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
    ];


    public function register()
    {
        $this->app->bind(AuthentificationInterface::class, function ($app) {
            // Choisir l'implémentation en fonction de la clé AUTH_DRIVER dans le .env
            if (env('AUTH_DRIVER') === 'firebase') {
                return new AuthentificationFirebase();
            }
            return new AuthentificationLocalPassport();
        });
    }
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();


        //
    }
}
