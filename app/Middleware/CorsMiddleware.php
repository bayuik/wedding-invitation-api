<?php

namespace App\Middleware;

use Closure;
use Core\Http\Request;
use Core\Http\Respond;
use Core\Middleware\MiddlewareInterface;

final class CorsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next)
    {
        // Allow all origins and disable any CORS validation
        $header = respond()->getHeader();
        $header->set('Access-Control-Allow-Origin', '*'); // Allow any origin
        $header->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS'); // Allow all HTTP methods
        $header->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With'); // Allow common headers
        $header->set('Access-Control-Allow-Credentials', 'true'); // Allow credentials if needed

        // Preflight request handling (OPTIONS)
        if ($request->method(Request::OPTIONS)) {
            return respond()->setCode(Respond::HTTP_NO_CONTENT); // 204 No Content for OPTIONS requests
        }

        return $next($request); // Continue to next middleware if not OPTIONS
    }
}
