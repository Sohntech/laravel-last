<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Referenciel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'referenciel';
    }
}
