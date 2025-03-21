<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 检查必需的请求头字段
        $requiredHeaders = ['uuid', 'model', 'os'];
        
        // foreach ($requiredHeaders as $header) {
        //     if (!$request->hasHeader($header)) {
        //         return response()->json([
        //             'code' => 400,
        //             'message' => "缺少必需的请求头: {$header}"
        //         ], 400);
        //     }
        // }
        
        return $next($request);
    }
}
