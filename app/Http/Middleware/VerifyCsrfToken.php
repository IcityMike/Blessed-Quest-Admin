<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'callback-webhook',
        'nium-payment-status-webhook',
        'inbound-direct-credit-callback',
        'npp-return-callback',
        'npp-payment-status-callback',
        'direct-entry-dishonour-callback',
        'swiftId-company-type-webhook',
        'swiftId-trust-corporate-type-webhook',
        'swiftId-individual-type-webhook',
        'swiftId-trust-individual-type-webhook',
        'swiftId-joint-type-webhook',
        'swiftId-update-instance-notification',


    ];
}
