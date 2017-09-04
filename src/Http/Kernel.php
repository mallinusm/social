<?php

namespace Social\Http;

use Barryvdh\Cors\HandleCors;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\{
    CheckForMaintenanceMode,
    ConvertEmptyStringsToNull,
    ValidatePostSize
};
use Illuminate\Routing\Middleware\{
    SubstituteBindings,
    ThrottleRequests
};
use Social\Http\Middleware\{
    OnlyJsonAllowed,
    TrimStrings
};

/**
 * Class Kernel
 * @package Social\Http
 */
final class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [
            'cors',
            'json',
            'throttle:60,1',
            'bindings',
        ],
        'cdn' => [
            'cors'
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'bindings' => SubstituteBindings::class,
        'throttle' => ThrottleRequests::class,
        'cors' => HandleCors::class,
        'json' => OnlyJsonAllowed::class
    ];
}
