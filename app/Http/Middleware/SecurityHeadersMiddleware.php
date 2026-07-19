<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add standard security headers
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        $cspRules = [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            "form-action 'self' https://wa.me https://api.whatsapp.com",
            "img-src 'self' data: blob: https:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net",
            "connect-src 'self' https:",
            "media-src 'self' blob: data:",
            "manifest-src 'self'",
            "worker-src 'self' blob:"
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $cspRules));

        return $response;
    }
}
