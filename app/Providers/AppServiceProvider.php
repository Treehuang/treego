<?php

namespace App\Providers;

use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use function PHPSTORM_META\type;

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

   	    // 捕获验证异
   	    API::error(function (\Dingo\Api\Exception\ValidationHttpException $exception) {
   	        $errors =$exception->getErrors()->getMessages();
   	        foreach ($errors as $key => $value) {
   	            $errors[$key] = $value[0];
            }
   	        return response()->json(['errors' => $errors], 422);
   	    });

        // 捕获认证异常
        API::error(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
            return response()->json(['errors' => $exception->getMessage()], 401);
        });
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
