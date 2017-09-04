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
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

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
