<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = ['rw', 'en', 'sw'];
        
        // Check query parameter ?lang=, then session('lang'), fallback to 'en'
        $lang = $request->query('lang') ?? session('lang') ?? 'en';
        
        if (!in_array($lang, $supported, true)) {
            $lang = 'en';
        }
        
        session(['lang' => $lang]);
        App::setLocale($lang);

        // Capture affiliate referral code
        if ($request->has('ref')) {
            $refId = intval($request->query('ref'));
            if ($refId > 0) {
                session(['ref_user_id' => $refId]);
            }
        }
        
        return $next($request);
    }
}
