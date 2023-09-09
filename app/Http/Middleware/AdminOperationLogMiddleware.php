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

use App\Jobs\AdminOperationLogJob;
use Closure;
use Illuminate\Http\Request;

class AdminOperationLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $environment = app()->environment();

        $response = $next($request);


        if ($environment === 'production') {
            if (in_array(strtoupper($request->method()), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
                dispatch(new AdminOperationLogJob([
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->getClientIp(),
                    'params' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                    'response_params' => $response->getContent(),
                    'admin_user_id' => $request->user() ? $request->user()->id : 0,
                    'status_code' => $response->getStatusCode()
                ]));
            }
        }
        return $response;
    }
}
