<?php

namespace Social\Exceptions;

use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\{
    JsonResponse, Request
};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

/**
 * Class Handler
 * @package Social\Exceptions
 */
final class Handler extends ExceptionHandler
{
    /**
     * @var array
     */
    private $map = [
        Response::HTTP_NOT_FOUND => [
            ModelNotFoundException::class,
            FileNotFoundException::class,
            EntityNotFoundException::class
        ],
        Response::HTTP_FORBIDDEN => [
            AuthorizationException::class
        ],
        Response::HTTP_NOT_ACCEPTABLE => [
            NotAcceptableHttpException::class
        ]
    ];

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        foreach ($this->map as $statusCode => $exceptionMap) {
            foreach ($exceptionMap as $exception) {
                if ($e instanceof $exception) {
                    return new JsonResponse(['error' => $e->getMessage()], $statusCode);
                }
            }
        }

        return parent::render($request, $e);
    }
}
