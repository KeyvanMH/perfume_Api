<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomMiddleware
{
    protected $encryptCookies;

    protected $addQueuedCookiesToResponse;

    protected $startSession;

    protected $shareErrorsFromSession;

    protected $substituteBindings;
    //    protected $validateCsrfToken;

    public function __construct(
        EncryptCookies $encryptCookies,
        AddQueuedCookiesToResponse $addQueuedCookiesToResponse,
        StartSession $startSession,
        ShareErrorsFromSession $shareErrorsFromSession,
        SubstituteBindings $substituteBindings,
        //        ValidateCsrfToken $validateCsrfToken
    ) {
        $this->encryptCookies = $encryptCookies;
        $this->addQueuedCookiesToResponse = $addQueuedCookiesToResponse;
        $this->startSession = $startSession;
        $this->shareErrorsFromSession = $shareErrorsFromSession;
        $this->substituteBindings = $substituteBindings;
        //        $this->validateCsrfToken = $validateCsrfToken;
    }

    public function handle($request, Closure $next)
    {
        $response = $this->encryptCookies->handle($request, function ($request) use ($next) {
            return $this->addQueuedCookiesToResponse->handle($request, function ($request) use ($next) {
                return $this->startSession->handle($request, function ($request) use ($next) {
                    return $this->shareErrorsFromSession->handle($request, function ($request) use ($next) {
                        //                        return $this->validateCsrfToken->handle($request, function ($request) use ($next) {
                        return $this->substituteBindings->handle($request, $next);
                        //                        });
                    });
                });
            });
        });

        return $response;
    }
}
