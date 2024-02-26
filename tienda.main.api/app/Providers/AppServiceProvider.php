<?php

namespace App\Providers;

use ReflectionException;
use Illuminate\Support\ServiceProvider;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        app('Dingo\Api\Exception\Handler')->register(function (TokenExpiredException $exception) {
            return response()->json([
                'message' => 'Authentication Error',
                'errors' => ['The token has expired']
            ], 401);
        });
        app('Dingo\Api\Exception\Handler')->register(function (TokenInvalidException $exception) {
            return response()->json([
                'message' => 'Authentication Error',
                'errors' => ['Could not decode the token', 'The token signature could not be verified']
            ], 401);
        });
        app('Dingo\Api\Exception\Handler')->register(function (TokenBlacklistedException $exception) {
            return response()->json([
                'message' => 'Authentication Error',
                'errors' => ['The token has already been used']
            ], 401);
        });
        app('Dingo\Api\Exception\Handler')->register(function (UnauthorizedHttpException $exception) {
            return response()->json([
                'message' => 'Authentication Error',
                'errors' => ['Invalid access credentials, check token']
            ], 401);
        });
        app('Dingo\Api\Exception\Handler')->register(function (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Query error',
                'errors' => ['The record you are trying to query does not exist']
            ], 404);
        });
        app('Dingo\Api\Exception\Handler')->register(function (NotFoundHttpException $exception) {
            return response()->json([
                'message' => 'Query error',
                'errors' => ['The resource you are trying to query does not exist']
            ], 404);
        });
        app('Dingo\Api\Exception\Handler')->register(function (ReflectionException $exception) {
            return response()->json([
                'message' => 'Integrality Error',
                'errors' => [$exception->getMessage()]
            ], 404);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
