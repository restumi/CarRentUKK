<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     */
    protected $headers = Request::HEADER_FORWARDED;
}
