<?php

namespace Social\Exceptions;

use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

/**
 * Class ExceptionCatcher
 * @package Social\Exceptions
 */
final class ExceptionCatcher
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
     * @param Exception $exception
     * @return int
     */
    public function catch(Exception $exception): ?int
    {
        foreach ($this->map as $statusCode => $exceptionMap) {
            foreach ($exceptionMap as $exceptionClass) {
                if ($exception instanceof $exceptionClass) {
                    return $statusCode;
                }
            }
        }

        return null;
    }
}
