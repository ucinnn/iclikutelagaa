<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

// Global Middleware
use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;

// Web Middleware
use App\Http\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;

// API Middleware
use Laravel\Fortify\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Laravel\Sanctum\HasApiTokens;

// Route Middleware
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureHasValidRole;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

// Custom Middleware
use App\Http\Middleware\SetLocale;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     */
    protected $middleware = [
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * Middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            EnsureFrontendRequestsAreStateful::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,

            \App\Overrides\FilamentLanguageSwitcher\SetLocale::class,
            SetLocale::class,
        ],

        'api' => [
            'throttle:api',
            SubstituteBindings::class,
        ],
    ];

    /**
     * Route middleware.
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'verified' => EnsureEmailIsVerified::class,
        'role' => EnsureHasValidRole::class,
        'module.access' => \App\Http\Middleware\CheckModuleAccess::class,
        'auth.user' => \App\Http\Middleware\EnsureUserGuard::class,
    ];
}
