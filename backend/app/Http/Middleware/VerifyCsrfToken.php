<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Allow the SPA to POST to this login endpoint without CSRF when using token auth
        'api/login',
        // If you also have other endpoints that need exclusion add them here.
    ];
}
