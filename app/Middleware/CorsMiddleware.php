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
        $header = respond()->getHeader();

        // Allow any origin
        $header->set('Access-Control-Allow-Origin', '*');

        // Expose headers
        $header->set('Access-Control-Expose-Headers', 'Authorization, Content-Type, Cache-Control, Content-Disposition');

        // Handle Vary header
        $vary = $header->has('Vary') ? explode(', ', $header->get('Vary')) : [];
        $vary = array_unique([...$vary, 'Accept', 'Origin', 'User-Agent', 'Access-Control-Request-Method', 'Access-Control-Request-Headers']);
        $header->set('Vary', join(', ', $vary));

        // Allow methods
        $header->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

        // Allow headers, including x-access-key
        $header->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, Access-Control-Allow-Origin, X-Access-Key');

        // Handle preflight requests (OPTIONS method)
        if ($request->method() === Request::OPTIONS) {
            return respond()->setCode(Respond::HTTP_NO_CONTENT);
        }

        return $next($request);
    }
}
