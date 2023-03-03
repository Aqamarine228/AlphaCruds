<?php

namespace Aqamarine\AlphaCruds\Http\Middlewares;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OnlyLocalMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (App::environment() == 'local') {
            return $next($request);
        }

        return new JsonResponse([
            'success' => false,
            'response' => ['To access CRUD generator app environment must be changed to local.'],
        ], 400);
    }
}
