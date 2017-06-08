<?php

namespace Social\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OnlyJsonAllowed
 * @package Social\Http\Middleware
 */
class OnlyJsonAllowed
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->expectsJson()) {
            return $next($request);
        }

        throw new RuntimeException('Only json format is supported.');
    }
}
