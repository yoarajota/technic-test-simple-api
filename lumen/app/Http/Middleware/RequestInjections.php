<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use Closure;

class RequestInjections
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($request->register)) {
            $model = Helpers::getModelByTableName($request->register);

            if ($model) {
                $request->merge(["model" => $model]);
            }
        }

        if (isset($request->body)) {
            $model = Helpers::getModelByTableName($request->register);

            if ($model) {
                $request->merge(["model" => $model]);
            }
        }

        return $next($request);
    }
}
