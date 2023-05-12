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

use App\Models\RequestLog;
use Closure;
use Illuminate\Http\Request;

class RequestLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $log = new RequestLog([
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->getClientIp(),
            'params' => json_encode($request->all(), JSON_UNESCAPED_UNICODE)
        ]);
        $log->save();
        $response = $next($request);
        if ($rep = $response->getContent()) {
            $log->update([
                'response_params' => $rep,
                'user_id' => $request->user() ? $request->user()->id : 0
            ]);
        }
        return $response;
    }
}
