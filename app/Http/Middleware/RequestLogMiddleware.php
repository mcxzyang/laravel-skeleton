<?php
/**
 * This file is part of the ${PROJECT_NAME}
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Middleware;

use App\Jobs\RequestLogJob;
use Closure;
use Illuminate\Http\Request;

class RequestLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $environment = app()->environment();

        $response = $next($request);

        if ($environment === 'production') {
            dispatch(new RequestLogJob([
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->getClientIp(),
                'params' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                'response_params' => $response->getContent(),
                'user_id' => $request->user() ? $request->user()->id : 0,
            ]));
        }
        return $response;
    }
}
