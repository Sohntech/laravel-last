<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\UserRepositoryImplement;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\ReferencielFirebaseModel;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Psr\Http\Message\UriInterface; //

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepositoryImplement::class);

        // $this->app->singleton('referenciel', function ($app) {
        //     return new ReferencielFirebaseModel($app->make('firebase.database'));
        // });

        // $this->app->singleton('referenciel', function ($app) {
        //     return new \App\Models\ReferencielFirebaseModel($app->make('firebase.database'));
        // });


            // Assurez-vous d'avoir le binding Firebase
    $this->app->singleton(Database::class, function ($app) {
        return (new Factory)
            // ->withServiceAccount(env('FIREBASE_CREDENTIALS'))
            // ->withDatabaseUri(config('firebase.database_url'))
            ->withServiceAccount(config('services.firebase.credentials'))
            ->withDatabaseUri(config('services.firebase.database_url')) // Pas besoin de withStorageBucket()
            ->createDatabase();
    });

    // Si UriInterface est requis, ajoutez le binding
    // $this->app->bind(UriInterface::class, function ($app) {
    //     // Vous devez retourner une instance d'UriInterface ici
    //     return $app->make(YourUriImplementationClass::class);
    // });

    $this->app->singleton('referenciel', function ($app) {
        return new ReferencielFirebaseModel($app->make(Database::class));
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
