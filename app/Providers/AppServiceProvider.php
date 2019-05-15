<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
   	    }
    }

    /**
     * Bootstrap any application services.
     *
     * @param Request $request
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Validator::extend('smsexist', function () use ($request) {
            $verifyData = \Cache::get($request->verify_key);
            if (!$verifyData){
                return false;
            }
            return true;
        });

        Validator::extend('smserror', function () use ($request){
            $verifyData = \Cache::get($request->verify_key);

            if (!hash_equals((string)$verifyData['code'], (string)$request->smscode)) {
                return false;
            }

            return true;
        });
    }
}
