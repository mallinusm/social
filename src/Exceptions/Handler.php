<?php

namespace Social\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\{
    JsonResponse,
    Request
};
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Handler
 * @package Social\Exceptions
 */
final class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated(Request $request, AuthenticationException $exception): Response
    {
        if ($request->expectsJson()) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('login'));
    }

    /**
     * @param Request $request
     * @param Exception $e
     * @return Response
     */
    public function render($request, Exception $e): Response
    {
        if ($statusCode = (new ExceptionCatcher)->catch($e)) {
            return new JsonResponse(['error' => $e->getMessage()], $statusCode);
        }

        return parent::render($request, $e);
    }
}
