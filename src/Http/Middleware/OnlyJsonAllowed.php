<?php

namespace Social\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

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

        throw new NotAcceptableHttpException('Only json format is supported.');
    }
}
